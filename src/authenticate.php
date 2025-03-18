<?php
    session_start();

    include "utils.php";

    if (isset($_SESSION["id"]) && !is_null($_SESSION["id"])) {
      header("location: calendar.php");
      exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = san_mail($_POST['email']);
        $password = san_pw($_POST['password']);

        if (!validate_pw($password) || !validate_mail($email)) {
            header("Location: connect.php?error");
            exit;
        }

        try {
            $pdo = connectDB();

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM USR_DT WHERE email = ?");
            $stmt->execute([$email]);
            $emailExists = $stmt->fetchColumn();

            if ($emailExists) {
                $stmt = $pdo->prepare("SELECT id, pwh, sub FROM USR_DT WHERE email = ?");
                $stmt->execute([$email]);

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = $row["id"];
                $pwh = $row["pwh"];
                $sub = $row["sub"];

                $hashed = password_hash($password, PASSWORD_BCRYPT);

                if (password_verify($password, $pwh)) {
                    if ($sub != 0) {
                        $_SESSION["id"] = $id;

                        header("Location: calendar.php");
                    } else {
                        header("Location: connect.php?mail-validate");
                        exit;
                    }
                } else {
                    header("Location: connect.php?error");
                    exit;
                }
            } else {
                header("Location: connect.php?error");
                exit;
            }
        } catch (PDOException $e) {
            header("Location: error.php?error=register-error");
            exit;
        }
    } else {
        header("Location: connect.php?error");
        exit;
    }
?>
