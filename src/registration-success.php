<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>WebCal - Connection</title>
        <link rel="icon" href="assets/icons/favicon-64.svg" type="image/svg+xml">

        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="styles/global.css">
    </head>
    <body page-name="registration-success">
        <include href="templates/header.html"></include>
        <main class="container pt-5 min-vh-100 d-flex justify-content-between flex-column">
            <section class="container align-items-center justify-content-center text-center">
                <?php
                    include "utils.php";

                    if (isset($_GET['code'])) {
                        $verificationCode = trim($_GET['code']);

                        try {
                            $pdo = connectDB();

                            // Check if the verification code exists in the PENDING_DT table
                            $stmt = $pdo->prepare("SELECT src FROM PENDING_DT WHERE validator = ?");
                            $stmt->execute([$verificationCode]);
                            $src = $stmt->fetchColumn();

                            if (!(!$src)) {
                                // Mark the user as verified in the USR_DT table
                                $stmt = $pdo->prepare("UPDATE USR_DT SET sub = true WHERE id = ?");
                                $stmt->execute([$src]);

                                // Remove the verification code from the PENDING_DT table
                                $stmt = $pdo->prepare("DELETE FROM PENDING_DT WHERE validator = ?");
                                $stmt->execute([$verificationCode]);

                                echo (file_get_contents("templates/register-success.html"));
                            } else {
                                echo (file_get_contents("templates/register-invalid-mail-error.html"));
                            }
                        } catch (PDOException $e) {
                            echo (file_get_contents("templates/register-error.html"));
                            echo ("Erreur : " . $e->getMessage());
                        }
                    } else {
                        echo (file_get_contents("templates/register-invalid-link-error.html"));
                    }
                ?>
            </section>
        </main>
        <include href="templates/footer.html"></include>
        <script src="js/script.js"></script>
        <script>
            // Redirect function
            let bar = document.getElementById("mailValidatedBar");
            if (bar != null) {
                setTimeout(() => {
                    window.location.href = "connect.php"; // Replace with your desired URL
                }, 5000);
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
