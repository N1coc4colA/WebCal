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

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $name = san_string($_POST['lastname'], 30);
                        $surname = san_string($_POST['firstname'], 30);
                        $birthdate = san_string($_POST['birthdate'], 20);
                        $address = san_string($_POST['address'], 50);
                        $phone = san_phone($_POST['phone']);
                        $email = san_mail($_POST['email']);
                        $password = san_pw($_POST['password']);
                        // We don't need the password confirmation field at all.

                        if (validate_mail($email)) {
                            if (validate_pw($password)) {
                                if (validate_phone($phone)) {
                                    // Vérification de l'unicité de l'email
                                    try {
                                        $pdo = connectDB();

                                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM USR_DT WHERE email = ?");
                                        $stmt->execute([$email]);
                                        $emailExists = $stmt->fetchColumn();

                                        if (!$emailExists) {
                                            // Generate PW hash
                                            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                                            // Insertion dans la base de données
                                            $stmt = $pdo->prepare("INSERT INTO USR_DT (name, surname, birthdate, address, phone, email, pwh) VALUES (?, ?, ?, ?, ?, ?, ?)");
                                            $stmt->execute([$name, $surname, $birthdate, $address, $phone, $email, $hashedPassword]);

                                            // Send verification mail
                                            // Generate entry first
                                            $date = date('Y-m-d');
                                            $time = date('H:i:s');

                                            $verificationCode = mb_strimwidth(bin2hex(random_bytes(20)), 0, 30, "");
                                            $verificationLink = "https://" . getenv("HOST_NAME") . "/registration-success.php?code=" . $verificationCode;

                                            // Get the user's ID
                                            $stmt = $pdo->prepare("SELECT id FROM USR_DT WHERE email = ?");
                                            $stmt->execute([$email]);
                                            $user_id = $stmt->fetchColumn();

                                            // Store verification code
                                            $stmt = $pdo->prepare("INSERT INTO PENDING_DT (sub_date, sub_time, validator, src) VALUES (?, ?, ?, ?)");
                                            $stmt->execute([$date, $time, $verificationCode, $user_id]);

                                            $subject = "Vérification de votre email";
                                            $message = "Bonjour " . $surname . ",\n\nCliquez sur <a href=\"" . $verificationLink . "\">sur ce lien</a> pour vérifier votre email.\nCordialement,\nL'équipe d'inscription.";
                                            $plain = "Bonjour " . $surname . ",\nUtilisez le lien suivant pour vérifier votre email : " . $verificationLink . "\n\nCordialement,\nL'équipe d'inscription.";

                                            if (sendMail($email, $subject, $message, $plain)) {
                                                error_log("Registered user: " . $email, 0);
                                                echo (file_get_contents("templates/register-mail-success.html"));
                                                echo "<p>Mail: " . $email . "</p>";
                                            } else {
                                                echo (file_get_contents("templates/register-mail-error.html"));
                                            }
                                        } else {
                                            echo (file_get_contents("templates/register-mail-success.html"));
                                            echo "<p>Registered !</p>";
                                        }
                                    } catch (PDOException $e) {
                                        echo (file_get_contents("templates/register-error.html"));
                                        echo ("Erreur : " . $e->getMessage());
                                    }
                                } else {
                                    echo (file_get_contents("templates/register-phone-error.html"));
                                }
                            } else {
                                echo (file_get_contents("templates/register-pw-error.html"));
                            }
                        } else {
                            echo (file_get_contents("templates/register-mail-error.html"));
                        }
                    }
                ?>
            </section>
        </main>
        <include href="templates/footer.html"></include>
        <script src="js/script.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
