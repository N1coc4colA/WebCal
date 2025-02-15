<!-- HTML -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>
    <script>
        function validateForm() {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const phone = document.getElementById('phone');

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                alert('Veuillez entrer une adresse email valide.');
                return false;
            }

            if (password.value.length < 6) {
                alert('Le mot de passe doit contenir au moins 6 caractères.');
                return false;
            }

            const phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(phone.value)) {
                alert('Veuillez entrer un numéro de téléphone valide (10 chiffres).');
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <form action="process_registration.php" method="POST" onsubmit="return validateForm()">
        <label for="lastname">Nom :</label>
        <input type="text" id="lastname" name="lastname" required><br>

        <label for="firstname">Prénom :</label>
        <input type="text" id="firstname" name="firstname" required><br>

        <label for="birthdate">Date de naissance :</label>
        <input type="date" id="birthdate" name="birthdate" required><br>

        <label for="address">Adresse postale :</label>
        <input type="text" id="address" name="address" required><br>

        <label for="phone">Numéro de téléphone :</label>
        <input type="text" id="phone" name="phone" required><br>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>

<!-- PHP (process_registration.php) -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $birthdate = htmlspecialchars(trim($_POST['birthdate']));
    $address = htmlspecialchars(trim($_POST['address']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = htmlspecialchars(trim($_POST['password']));

    if (!$email) {
        die('Adresse email invalide.');
    }

    if (strlen($password) < 6) {
        die('Le mot de passe doit contenir au moins 6 caractères.');
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

        echo 'Inscription réussie !';
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
?>
