<?php
  session_start();

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
  </head>
  <body page-name="index">
    <include href="templates/header.php"></include>
    <main class="pt-5 min-vh-100">
      <section class="container pt-3">
        <div class="row">
          <div class="col-12 col-lg-8 mb-3">
            <div>
              <div class="calendar-header-bar d-flex justify-content-between align-items-center mb-3">
                <button id="prevMonth" class="btn btn-primary">
                  <i class="bi bi-chevron-left"></i>
                </button>
                <h2 id="monthYear" class="text-center">February 2025</h2>
                <button id="nextMonth" class="btn btn-primary">
                  <i class="bi bi-chevron-right"></i>
                </button>
              </div>
              <div class="d-flex justify-content-between calendar-header-ctr">
                <!-- Days of the week -->
                <div class="text-center split7 calendar-header">Sun</div>
                <div class="text-center split7 calendar-header">Mon</div>
                <div class="text-center split7 calendar-header">Tue</div>
                <div class="text-center split7 calendar-header">Wed</div>
                <div class="text-center split7 calendar-header">Thu</div>
                <div class="text-center split7 calendar-header">Fri</div>
                <div class="text-center split7 calendar-header">Sat</div>
              </div>
            </div>
            <div class="calendar-body">
              <div class="d-flex justify-content-between">
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline unused-day">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline striped-day">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline selected-day">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline current-day">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline selected-day current-day">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center">0</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-4">
            <h2 class="upcoming-header">Événements à venir:</h2>
            <div class="upcoming-body">
              <div class="d-flex flex-column">
                <div class="upcoming-entry d-flex flex-row">
                  <div class="d-flex flex-column mrg-l-5">
                    <p class="rdv-date text-center m-0">5</p>
                    <p class="rdv-time text-center m-0">12:00 - 13:00</p>
                  </div>
                  <div class="upcoming-sep"></div>
                  <div>
                    <p class="rdv-name">Jordan B.</p>
                    <p class="rdv-title">Bonjour !</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
    <include href="templates/footer.php"></include>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
