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
  </head>
  <body page-name="calendar">
    <include href="templates/header-connected.html"></include>
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
                <div class="text-center split7 calendar-header">Dim</div>
                <div class="text-center split7 calendar-header">Lun</div>
                <div class="text-center split7 calendar-header">Mar</div>
                <div class="text-center split7 calendar-header">Mer</div>
                <div class="text-center split7 calendar-header">Jeu</div>
                <div class="text-center split7 calendar-header">Ven</div>
                <div class="text-center split7 calendar-header">Sam</div>
              </div>
            </div>
            <div class="calendar-body">
              <div class="d-flex justify-content-between">
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
              </div>
              <div class="d-flex justify-content-between">
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
                </div>
                <div class="day-entry split7 d-flex flex-column justify-content-between align-items-baseline">
                  <p class="day-text">0</p>
                  <p class="day-tickets text-center hidden">0</p>
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
                    <p class="rdv-month text-center m-0 text-mutted">Novembre</p>
                    <p class="rdv-date text-center m-0">5</p>
                    <p class="rdv-time text-center m-0">12:00 - 13:00</p>
                  </div>
                  <div class="upcoming-sep"></div>
                  <div class="entry-content">
                    <p class="rdv-title">Bonjour !</p>
                  </div>
                  <i class="bi bi-trash3-fill event-rm-btn"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="mod-reservation-popup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod-resTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="mod-resTitle"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div id="mod-reservation-body" class="modal-body">
                <div>
                  <p id="mod-nothingAvailable" class="hidden-full">Rien de disponible :/</p>
                  <p id="mod-resResponseError" class="hidden-full">Une erreur est survenue :/</p>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  </div>
                </div>
                <form id="mod-resResponseOk" action="reserve-slot.php" method="POST" class="hidden-full">
                  <div class="input-group mb-3">
                    <label class="input-group-text" for="mod-timeSelect">Créneau</label>
                    <select class="form-select" id="mod-timeSelect" name="time">
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="basic-url" class="form-label" for="mod-message">Message</label>
                    <div class="form-text" id="form-message-info">200 caractères maximum.</div>
                    <div class="input-group">
                      <textarea id="mod-message" name="message" class="form-control" aria-label="Message area" maxlength="200"></textarea>
                    </div>
                  </div>
                  <input id="mod-dateSelect" name="date" type="hidden" value="2025-06-28">
                  <input name="token" type="hidden" value="<?php echo buildToken('slot-res-token'); ?>">
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Réserver</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="mod-events-popup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod-evTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="mod-evTitle"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div id="mod-reservation-body" class="modal-body">
                <div>
                  <p id="mod-noEvent" class="hidden-full">Rien de disponible :/</p>
                  <p id="mod-evResponseError" class="hidden-full">Une erreur est survenue :/</p>
                </div>
                <div id="mod-evResponseOk" class="hidden-full">
                  <div id="mod-evContainer"></div
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <?php

        if (isset($_GET['alert'])) {
          echo "
          <div class=\"toast-container position-fixed bottom-0 end-0 p-3\">
            <div id=\"toast-notifier\" class=\"toast\" role=\"alert\" aria-live=\"assertive\" aria-atomic=\"true\">
              <div class=\"toast-header\">";
          if ($_GET["alert"] == "success") {
            echo "<i class=\"bi bi-check-circle-fill\" style=\"color: var(--bs-teal);\"></i>";
            echo "<strong class=\"me-auto\">Succès de l'opération</strong>";
          } else {
            echo "<i class=\"bi bi-exclamation-triangle-fill\" style=\"color: var(--bs-warning);\"></i>";
            echo "<strong class=\"me-auto\">Échec de l'opération</strong>";
          }
          echo "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"toast\" aria-label=\"Close\"></button>
              </div>
            <div class=\"toast-body\">";

          if ($_GET["alert"] == "success") {
            echo "Le créneau a été réservé.";
          } else if ($_GET["alert"] == "error-meth") {
            echo "Échec de l'opération.";
          } else if ($_GET["alert"] == "error") {
            echo "Erreur interne, échec de l'opération.";
          } else {
            print_r($_GET); 
            echo "Message vide.";
          }

          echo "</div>
            </div></div>
          ";
        }

      ?>
    </main>
    <include href="templates/footer.html"></include>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/calendar.js"></script>
    <script src="js/toaster.js"></script>
  </body>
</html>
