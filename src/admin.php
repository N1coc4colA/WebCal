<?php
  include "session_utils.php";

  if (!isset($_SESSION["id"])  || is_null($_SESSION["id"])) {
    header("location: connect.php");
    exit;
  }
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Calendrier WebCal</title>
    <link rel="icon" href="assets/icons/favicon.svg" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/calendar.css">
    <link rel="stylesheet" href="styles/admin.css">
  </head>
  <body page-name="calendar">
    <include href="templates/header-connected.html"></include>
    <main class="pt-5 min-vh-100">
      <div>
        <section class="sidebar p-1 pt-3">
          <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
              <button class="nav-link" id="res-tab" data-bs-toggle="tab" data-bs-target="#res" type="button" role="tab" aria-controls="profile" aria-selected="false">
                <i class="bi bi-pencil-fill"></i>
                <span class="d-none d-sm-inline">Réservations du mois</span>
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" aria-controls="profile" aria-selected="false">
                <i class="bi bi-trash2-fill"></i>
                <span class="d-none d-sm-inline">Gestion utilisateurs</span>
              </button>
            </li>
          </ul>
        </section>
        <section class="tab-content">
          <div class="container col justify-content-center tab-pane fade show" id="res" role="tabpanel" aria-labelledby="res-tab">
            <include href="templates/calendar-header.html"></include>
            <div class="col mb-2">
              <h2 class="upcoming-header">Événements à venir:</h2>
              <button type="button" class="btn btn-primary mb-2" onclick="downloadUpcomingEvents()">
                <i class="bi bi-cloud-download-fill"></i>
                Télécharger
              </button>
              <div id="upcoming-body" class="upcoming-body hidden-full"></div>
              <p id="upcoming-body-noEvent" class="hidden-full">Rien de disponible :/</p>
              <p id="upcoming-body-evResponseError" class="hidden-full">Une erreur est survenue :/</p>
            </div>
          </div>
          <div class="container col justify-content-center tab-pane fade show" id="users" role="tabpanel" aria-labelledby="users-tab">
          </div>
        </section>
      </div>
    </main>
    <include href="templates/footer.html"></include>
    <dependency src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" order="0"></dependency>
    <dependency src="js/calendar-admin.js"></dependency>
    <dependency src="js/toaster.js"></dependency>
    <script src="js/script.js"></script>
    <script>
      document.getElementById("res-tab").classList.toggle("active");
      document.getElementById("res").classList.toggle("active");
    </script>
  </body>
</html>
