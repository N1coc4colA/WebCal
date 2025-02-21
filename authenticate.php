<!DOCTYPE html>
<html lang="fr">
    <?php
        include "utils.php";

        session_start();

        if (isset($_SESSION["id"]) && !is_null($_SESSION["id"])) {
          header("location: calendar.php");
          exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = mb_strimwidth(filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL), 0, 30, "");
            $password = mb_strimwidth(htmlspecialchars($_POST['password']), 0, 30, "");

            try {
                $pdo = connectDB();

                // [TODO] Check that the user validated mail
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM USR_DT WHERE email = ?");
                $stmt->execute([$email]);
                $emailExists = $stmt->fetchColumn();

                if (!(!$emailExists)) {
                    $stmt = $pdo->prepare("SELECT id, pwh FROM USR_DT WHERE email = ?");
                    $stmt->execute([$email]);

                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $id = $row["id"];
                    $pwh = $row["pwh"];

                    $hashed = password_hash($password, PASSWORD_BCRYPT);

                    if (password_verify($password, $pwh)) {
                        $_SESSION["id"] = $id;

                        header("Location: calendar.php");
                    } else {
                        header("Location: connect.php?error");
                        exit;
                    }
                } else {
                    header("Location: connect.php?error");
                    exit;
                }
            } catch (PDOException $e) {
                header("Location: error.php?error=register-error.html");
                exit;
            }
        } else {
            header("Location: connect.php?error");
            exit;
        }
    ?>
</html>
