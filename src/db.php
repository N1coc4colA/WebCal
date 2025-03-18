<?php
    class DB {
        private static $_instance = null;

        public static function getInstance() {
            if(is_null(DB::$_instance)) {
                DB::$_instance = new DB();  
                try {
                    $dsn = 'mysql:host=' . getenv("MYSQL_HOST") . ';dbname=' . getenv("MYSQL_DATABASE") . ';charset=utf8';
                    $username = getenv("MYSQL_USER");
                    $password_db = getenv("MYSQL_PASSWORD");
                
                    DB::$_instance = new PDO($dsn, $username, $password_db);
                    DB::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                } catch (PDOException $e) {
                    DB::$_instance = null;
                    die("Database connection failed: " . $e->getMessage());
                }
            }

            return DB::$_instance;
        }
    }
?>