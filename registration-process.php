<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $birthdate = htmlspecialchars(trim($_POST['birthdate']));
    $address = htmlspecialchars(trim($_POST['address']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = htmlspecialchars(trim($_POST['password']));
    $confirmPassword = htmlspecialchars(trim($_POST['confirm_password']));

    if (!$email) {
        die('Adresse email invalide.');
    }

    if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{6,}/', $password)) {
        die('Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un caractère spécial et au moins 6 caractères.');
    }

    if ($password !== $confirmPassword) {
        die('Les mots de passe ne correspondent pas.');
    }

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        die('Numéro de téléphone invalide.');
    }

    // Vérification de l'unicité de l'email
    $dsn = 'mysql:host=localhost;dbname=inscription_db;charset=utf8';
    $username = 'root';
    $password_db = '';

    try {
        $pdo = new PDO($dsn, $username, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            die('Cet email est déjà utilisé.');
        }

        // Insertion dans la base de données
        $stmt = $pdo->prepare('INSERT INTO users (lastname, firstname, birthdate, address, phone, email, password) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt->execute([$lastname, $firstname, $birthdate, $address, $phone, $email, $hashedPassword]);

        // Envoi d'un email de vérification
        $verificationCode = bin2hex(random_bytes(16));
        $verificationLink = "http://localhost/verify_email.php?code=$verificationCode";

        // Stocker le code de vérification
        $stmt = $pdo->prepare('INSERT INTO email_verifications (email, code) VALUES (?, ?)');
        $stmt->execute([$email, $verificationCode]);

        $subject = 'Vérification de votre email';
        $message = "Bonjour $firstname,\n\nCliquez sur le lien suivant pour vérifier votre email :\n$verificationLink\n\nCordialement,\nL'équipe d'inscription.";
        $headers = 'From: noreply@votresite.com';

        if (mail($email, $subject, $message, $headers)) {
            echo 'Inscription réussie ! Veuillez vérifier votre email.';
        } else {
            echo 'Erreur lors de l'envoi de l'email de vérification.';
        }
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
?>
