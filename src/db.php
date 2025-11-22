<?php
    class DB {
        private static $instance = null;
        private $connection = null;

        protected function __clone() {
            throw new \Exception("Cannot clone a singleton.");
        }

        public function __wakeup() {
            throw new \Exception("Cannot unserialize a singleton.");
        }

        protected function __construct() {
            try {
                $dsn = 'mysql:host=' . getenv("MYSQL_HOST") . ';dbname=' . getenv("MYSQL_DATABASE") . ';charset=utf8';
                $username = getenv("MYSQL_USER");
                $password_db = getenv("MYSQL_PASSWORD");

                $this->connection = new PDO($dsn, $username, $password_db);
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                DB::$_instance = null;
                die("Database connection failed: " . $e->getMessage());
            }
        }

        public static function getInstance() {
            if(is_null(DB::$instance)) {
                DB::$instance = new DB();
            }

            return DB::$instance;
        }

        public function getConnection() {
            return $this->connection;
        }

        public function prepare(...$params) {
            return $this->connection->prepare(...$params);
        }
    }

    class DBAtomic {
        /** @var PDO */
        private $pdo;
        /** @var bool True if this DBAtomic created the root transaction */
        private $isRoot = false;
        /** @var string|null Savepoint name when nested */
        private $savepoint = null;
        /** @var bool True when commit() has been called */
        private $committed = false;
        /** @var bool True when rollback() has been called */
        private $rolledback = false;

        /**
         * Create a DBAtomic scope.
         * If a PDO/DB instance is provided it will be used; otherwise the DB singleton is used.
         * If a transaction is already active, a SQL SAVEPOINT is created so nested atomic blocks
         * are supported where possible.
         *
         * @param PDO|DB|null $dbOrPdo
         * @throws Exception when transaction/savepoint cannot be started
         */
        public function __construct($dbOrPdo = null)
        {
            if ($dbOrPdo instanceof DB) {
                $this->pdo = $dbOrPdo->getConnection();
            } elseif ($dbOrPdo instanceof PDO) {
                $this->pdo = $dbOrPdo;
            } else {
                $this->pdo = DB::getInstance()->getConnection();
            }

            try {
                if (!$this->pdo->inTransaction()) {
                    // start a root transaction
                    if (!$this->pdo->beginTransaction()) {
                        throw new \Exception('Failed to begin transaction');
                    }
                    $this->isRoot = true;
                } else {
                    // nested: create a savepoint for this scope
                    $this->savepoint = 'sp_' . bin2hex(random_bytes(8));
                    $this->pdo->exec('SAVEPOINT ' . $this->quoteIdentifier($this->savepoint));
                }
            } catch (\Throwable $e) {
                // convert to Exception for the signature
                throw new \Exception('Could not start atomic DB operation: ' . $e->getMessage(), 0, $e);
            }
        }

        /**
         * Commit the current atomic scope. For nested scopes this releases the savepoint.
         * @throws Exception
         */
        public function commit()
        {
            if ($this->rolledback) {
                throw new \Exception('Cannot commit: transaction already rolled back');
            }
            if ($this->committed) {
                return;
            }

            try {
                if ($this->isRoot) {
                    $this->pdo->commit();
                } elseif ($this->savepoint !== null) {
                    // release the savepoint if supported. Some databases allow RELEASE SAVEPOINT.
                    // If it fails, ignore as it's optional.
                    try {
                        $this->pdo->exec('RELEASE SAVEPOINT ' . $this->quoteIdentifier($this->savepoint));
                    } catch (\Throwable $e) {
                        // ignore release errors
                    }
                }
                $this->committed = true;
            } catch (\Throwable $e) {
                throw new \Exception('Commit failed: ' . $e->getMessage(), 0, $e);
            }
        }

        /**
         * Rollback the current atomic scope. For nested scopes this rolls back to the savepoint.
         */
        public function rollback()
        {
            if ($this->rolledback) {
                return;
            }

            try {
                if ($this->isRoot) {
                    if ($this->pdo->inTransaction()) {
                        $this->pdo->rollBack();
                    }
                } elseif ($this->savepoint !== null) {
                    $this->pdo->exec('ROLLBACK TO SAVEPOINT ' . $this->quoteIdentifier($this->savepoint));
                }
            } catch (\Throwable $e) {
                // Destructors must not throw; rethrowing here is optional for non-destruct context.
                throw new \Exception('Rollback failed: ' . $e->getMessage(), 0, $e);
            } finally {
                $this->rolledback = true;
            }
        }

        /**
         * Ensure rollback if not committed when object is destroyed.
         */
        public function __destruct()
        {
            if (!$this->committed && !$this->rolledback) {
                try {
                    // best-effort rollback; do not throw from destructor
                    if ($this->isRoot) {
                        if ($this->pdo->inTransaction()) {
                            $this->pdo->rollBack();
                        }
                    } elseif ($this->savepoint !== null) {
                        $this->pdo->exec('ROLLBACK TO SAVEPOINT ' . $this->quoteIdentifier($this->savepoint));
                    }
                } catch (\Throwable $e) {
                    // swallow any exceptions in destructor
                }
            }
        }

        /**
         * Convenience helper to run a callable inside an atomic block.
         * The callable receives the PDO instance as its first argument.
         * If the callable returns normally the transaction is committed, otherwise rolled back.
         *
         * @param callable $fn function(PDO $pdo): mixed
         * @param PDO|DB|null $dbOrPdo
         * @return mixed The callable's return value
         * @throws Throwable rethrows any exception/error from the callable after rollback
         */
        public static function run(callable $fn, $dbOrPdo = null)
        {
            $atomic = new self($dbOrPdo);
            try {
                $result = $fn($atomic->pdo);
                $atomic->commit();
                return $result;
            } catch (\Throwable $e) {
                // attempt rollback then rethrow
                try {
                    $atomic->rollback();
                } catch (\Throwable $ignore) {
                    // ignore rollback errors, but we still rethrow original
                }
                throw $e;
            }
        }

        /**
         * Quote an identifier (savepoint name). We keep it simple: only allow [A-Za-z0-9_].
         * This avoids SQL injection via savepoint names.
         */
        private function quoteIdentifier(string $ident): string
        {
            // allow only alnum and underscore; replace others with underscore
            $safe = preg_replace('/[^A-Za-z0-9_]/', '_', $ident);
            return $safe;
        }
    }
?>