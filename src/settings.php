<?php
  include "session_utils.php";

  try {
    $pdo = connectDB();

    $stmt = $pdo->prepare("SELECT name, surname, birthdate, address, phone, email FROM USR_DT WHERE (id=?)");
    $stmt->execute([$_SESSION["id"]]);
  
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    header("Location: error.php?error=sql-error");
    exit;
  }
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>WebCal - Paramètres</title>
    <link rel="icon" href="assets/icons/favicon.svg" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Outfit:wght@100..900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/settings.css">
  </head>
  <body page-name="settings">
    <include href="templates/header-connected.html"></include>
    <main class="pt-5 min-vh-100">
      <div>
        <section class="sidebar p-1 pt-3">
          <ul class="nav nav-pills nav-fill">
            <li class="nav-item">
              <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab" aria-controls="profile" aria-selected="false">
                <i class="bi bi-pencil-fill"></i>
                <span class="d-none d-sm-inline">Informations</span>
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="pw-tab" data-bs-toggle="tab" data-bs-target="#pw" type="button" role="tab" aria-controls="profile" aria-selected="false">
                <i class="bi bi-asterisk"></i>
                <span class="d-none d-sm-inline">Mot de passe</span>
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link" id="suppr-tab" data-bs-toggle="tab" data-bs-target="#suppr" type="button" role="tab" aria-controls="profile" aria-selected="false">
                <i class="bi bi-trash2-fill"></i>
                <span class="d-none d-sm-inline">Suppression</span>
              </button>
            </li>
          </ul>
        </section>
        <section class="tab-content">
          <div class="container col justify-content-center tab-pane fade show" id="edit" role="tabpanel" aria-labelledby="general-tab">
            <form action="update-information.php" method="POST">
              <h1 class="mt-3">Vos informations</h1>
              <div class="row">
                <div class="col-12 col-md-6">
                  <div class="input-group mb-3">
                    <label class="input-group-text" for="lastname">Nom</label>
                    <input class="form-control" type="text" id="lastname" name="lastname" maxlength="30" minlength="3" pattern="^[A-Za-zÀ-ÿ'\\-\\s]+$" required
                    value="<?php echo $row["name"];?>">
                  </div>
                  <div class="input-group mb-3">
                    <label class="input-group-text" for="firstname">Prénom</label>
                    <input class="form-control" type="text" id="firstname" name="firstname" maxlength="30" minlength="3" pattern="^[A-Za-zÀ-ÿ'\\-\\s]+$" required
                    value="<?php echo $row["surname"];?>">
                  </div>
                  <div class="input-group mb-3">
                    <label class="input-group-text" for="birthdate">Date de naissance</label>
                    <input class="form-control" type="date" id="birthdate" name="birthdate" required
                    value="<?php echo $row["birthdate"];?>">
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="input-group mb-3">
                    <label class="input-group-text" for="address">Adresse postale</label>
                    <input class="form-control" type="text" id="address" name="address" maxlength="50" required
                    value="<?php echo $row["address"];?>">
                  </div>
                  <div class="input-group mb-3">
                    <label class="input-group-text" for="phone">Téléphone</label>
                    <input class="form-control" type="tel" id="phone" minlength="10" maxlength="10" pattern="^[0-9]{10}$" name="phone" required
                    value="<?php echo str_pad((string)$row["phone"], 10, "0", STR_PAD_LEFT); ?>">
                  </div>
                  <div class="input-group mb-3">
                    <label class="input-group-text" for="email">Mail</label>
                    <input class="form-control" type="email" id="email" name="email" maxlength="30" required
                    value="<?php echo $row["email"];?>">
                  </div>
                </div>
              </div>
              <button class="btn btn-success" type="submit">
                <i class="bi bi-person-fill-up"></i>
                Mettre à jour
              </button>
              <input name="token" type="hidden" value="<?php echo buildToken('info-upd-token'); ?>">
            </form>
          </div>
          <div class="container col tab-pane fade show" id="pw" role="tabpanel" aria-labelledby="pw-tab">
            <form action="change-password.php" method="POST" class="container">
              <h1 class="mt-3">Changer de mot de passe</h1>
              <div class="input-group mb-3">
                <label class="input-group-text" for="password">Ancien mot de passe</label>
                <input class="form-control" type="password" id="password" name="password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}" minlength="8" maxlength="30" required>
                <i class="input-group-text bi bi-eye-slash" id="togglePassword"></i>
              </div>
              <div class="input-group mb-3">
                <label class="input-group-text" for="nPassword">Nouveau mot de passe</label>
                <input class="form-control" type="password" id="nPassword" name="nPassword" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}" minlength="8" maxlength="30" required>
                <i class="input-group-text bi bi-eye-slash" id="toggleNPassword"></i>
              </div>
              <div class="input-group mb-3">
                <label class="input-group-text" for="nPasswordConf">Confirmez</label>
                <input class="form-control" type="password" id="nPasswordConf" name="nPasswordConf" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}" minlength="8" maxlength="30" required>
                <i class="input-group-text bi bi-eye-slash" id="toggleNPasswordConf"></i>
              </div>
              <button class="btn btn-warning" type="submit">
                <i class="bi bi-shield-lock-fill"></i>
                Changer
              </button>
              <input name="token" type="hidden" value="<?php echo buildToken('pw-ch-token'); ?>">
            </form>
          </div>
          <div class="container col justify-content-center tab-pane fade show" id="suppr" role="tabpanel" aria-labelledby="suppr-tab">
            <h1 class="mt-3">Supprimer vos informations</h1>
            <div class="row">
              <form action="remove-information.php?data" method="POST" class="col-12 col-md-6 mb-3">
                <h3>Supprimer les données</h3>
                <p>Cela supprimera tous vos rendez-vous, mais pas vos informations personnelles.</p>
                <button class="btn btn-danger" type="submit">
                    <i class="bi bi-box2-fill"></i>
                    Supprimer les données
                </button>
                <input name="token" type="hidden" value="<?php echo buildToken('rm-data-token'); ?>">
              </form>
              <form action="remove-information.php?account" method="POST" class="col-12 col-md-6 mb-3">
                <h3>Supprimer votre compte</h3>
                <p>Cette action supprimera toutes les données du compte. Vous n'aurez plus accès à rien.</p>
                <button class="btn btn-danger" type="submit">
                  <i class="bi bi-person-fill-dash"></i>
                  Supprimer le compte
                </button>
                <input name="token" type="hidden" value="<?php echo buildToken('rm-account-token'); ?>">
              </form>
            </div>
          </div>
        </section>
      </div>
      <?php

        if (isset($_GET['alert'])) {
          echo "
          <div class=\"toast-container position-fixed bottom-0 end-0 p-3\">
            <div id=\"toast-notifier\" class=\"toast\" role=\"alert\" aria-live=\"assertive\" aria-atomic=\"true\">
              <div class=\"toast-header\">";
          if ($_GET["alert"] == "success-upd" || $_GET["alert"] == "success-rm" || $_GET["alert"] == "success-ch-pw" || $_GET["alert"] == "mail-send-error") {
            echo "<i class=\"bi bi-check-circle-fill\" style=\"color: var(--bs-teal);\"></i>";
            echo "<strong class=\"me-auto\">Succès de l'opération</strong>";
          } else {
            echo "<i class=\"bi bi-exclamation-triangle-fill\" style=\"color: var(--bs-warning);\"></i>";
            echo "<strong class=\"me-auto\">Échec de l'opération</strong>";
          }
          echo "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"toast\" aria-label=\"Close\"></button>
              </div>
              <div class=\"toast-body\">";

          if ($_GET["alert"] == "success-upd") {
            echo "La mise à jour des information a été effectuée.";
          } else if ($_GET["alert"] == "success-upd-mail") {
            echo "Action effectuée. N'oubliez pas de valider votre mail.";
          } else if ($_GET["alert"] == "mail-send-error") {
            echo "Echec de l'envoi du mail de vérification.";
          } else if ($_GET["alert"] == "success-rm") {
            echo "La suppression des information a été effectuée.";
          } else if ($_GET["alert"] == "success-ch-pw") {
            echo "Le changement de mot de passe a été efffectué.";
          } else if ($_GET["alert"] == "pw-error-meth") {
            echo "Échec de la requête.";
          } else if ($_GET["alert"] == "pw-error") {
            echo "Mauvais mot de passe.";
          } else if ($_GET["alert"] == "rm-error-meth") {
            echo "Échec de la requête.";
          } else if ($_GET["alert"] == "upd-error-meth") {
            echo "Échec de la requête.";
          } else if ($_GET["alert"] == "phone-error") {
            echo "Il y a une erreur avec votre numéro de téléphone.";
          } else if ($_GET["alert"] == "mail-error") {
            echo "Il semblerait que le mail ne soit pas bon, ou déjà utilisé.";
          } else if ($_GET["alert"] == "success-upd") {
            echo "Les données ont été mises à jour.";
          } else {
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
    <script src="js/toaster.js"></script>
    <script>
            if (window.location.hash) {
              console.log("-" + window.location.hash + "-");

              if (window.location.hash === "#edit") {
                document.getElementById("edit-tab").classList.toggle("active");
                document.getElementById("edit").classList.toggle("active");
              } else if (window.location.hash === "#pw") {
                document.getElementById("pw-tab").classList.toggle("active");
                document.getElementById("pw").classList.toggle("active");
              } else if (window.location.hash === "#suppr") {
                document.getElementById("suppr-tab").classList.toggle("active");
                document.getElementById("suppr").classList.toggle("active");
              } else {
                document.getElementById("edit-tab").classList.toggle("active");
                document.getElementById("edit").classList.toggle("active");
              }
            } else {
                document.getElementById("edit-tab").classList.toggle("active");
                document.getElementById("edit").classList.toggle("active");
            }

            function validateForm() {
                const nPassword = document.getElementById('nPassword');
                const confirmNPassword = document.getElementById('nPasswordConf');

                if (nPassword.value !== confirmNPassword.value) {
                    alert('Les mots de passe ne correspondent pas.');
                    return false;
                }

                return true;
            }

            const nPasswordToggleConf = document.getElementById('toggleNPasswordConf');
            const nPasswordConf = document.getElementById('nPasswordConf');
            nPasswordToggleConf.addEventListener('click', () => {
                // Toggle the type attribute using
                // getAttribure() method
                const type = nPasswordConf.getAttribute('type') === 'password' ? 'text' : 'password';
                nPasswordConf.setAttribute('type', type);
                // Toggle the eye and bi-eye icon
                nPasswordToggleConf.classList.toggle('bi-eye');
            });

            const passwordToggle = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            passwordToggle.addEventListener('click', () => {
                // Toggle the type attribute using
                // getAttribure() method
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // Toggle the eye and bi-eye icon
                passwordToggle.classList.toggle('bi-eye');
            });

            const nPasswordToggle = document.getElementById('toggleNPassword');
            const nPassword = document.getElementById('nPassword');
            nPasswordToggle.addEventListener('click', () => {
                // Toggle the type attribute using
                // getAttribure() method
                const type = nPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                nPassword.setAttribute('type', type);
                // Toggle the eye and bi-eye icon
                nPasswordToggle.classList.toggle('bi-eye');
            });
    </script>
  </body>
</html>
