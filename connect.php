<?php
  if (isset($_SESSION["id"]) && $_SESSION["id"] == true) {
    header("Location: calendar.php");
    exit;
  }
?>
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
    <body page-name="connect">
        <include href="templates/header.php"></include>
        <main class="container pt-5 min-vh-100 d-flex justify-content-between flex-column">
            <div class="d-flex flex-column justify-content-center">
                <div class="card mt-5">
                    <div class="card-body">
                        <h5 class="card-title">Se connecter</h5>
                        <?php
                            if (isset($_GET['error'])) {
                                echo "<p>Échec de l'authentification.</p>";
                            }
                        ?>
                        <form action="authenticate.php" method="POST">
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="email">Mail</label>
                                <input class="form-control" type="email" id="email" name="email" maxlength="30" required>
                            </div>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="password">Mot de passe</label>
                                <input class="form-control" type="password" id="password" name="password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" maxlength="30" required>
                                <i class="input-group-text bi bi-eye-slash" id="togglePassword"></i>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-center mt-5">
                                <a type="button" class="btn btn-secondary m-2" href="index.php">
                                    <i class="bi bi-arrow-return-left"></i>
                                    Retour
                                </a>
                                <button class="btn btn-success m-2" type="submit">
                                    Se connecter
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <include href="templates/footer.php"></include>
        <script src="js/script.js"></script>
        <script>
            const passwordToggle = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            passwordToggle.addEventListener('click', () => {
                // Toggle the type attribute using
                // getAttribure() method
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // Toggle the eye and bi-eye icon
                passwordToggle.classList.toggle('bi-eye');
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
