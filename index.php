<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>WebCal - Accueil</title>
    <link rel="icon" href="assets/icons/favicon-64.svg" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/global.css">
  </head>
  <body page-name="index">
    <include href="templates/header.php"></include>
    <main class="pt-5 min-vh-100 d-flex justify-content-between flex-column">
      <div class="container mt-5 pt-5">
        <div class="row mb-2">
          <img src="assets/icons/favicon-256.svg" width="256" height="256" class="col-12 col-sm-6 d-inline-block align-center" alt="">
          <h2 class="col-12 col-sm-6 align-center text-center align-content-center pt-5">Bienvenue sur WebCal !</h2>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-center mt-5">
          <a type="button" class="btn btn-primary m-2" href="connect.php">
            <i class="bi bi-person-bounding-box"></i>
            Se connecter
          </a>
          <a type="button" class="btn btn-secondary m-2" href="register.php">
            <i class="bi bi-person-add"></i>
            Créer un compte
          </a>
        </div>
      </div>
    </main>
    <include href="templates/footer.php"></include>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
