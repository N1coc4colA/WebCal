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
    <?php
        session_start();

        $result = "";
        if (isset($_SESSION["id"])) {
            echo "<include href=\"templates/header-connected.html\"></include>";

            include "utils.php";

            try {
                $pdo = connectDB();
                $stmt = $pdo->prepare("SELECT name, surname, email FROM USR_DT WHERE (id=?)");
                $stmt->execute([$_SESSION["id"]]);

                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                unset($result);
            }
        } else {
            echo "<include href=\"templates/header.html\"></include>";
            unset($result);
        }
    ?>
    <main class="container pt-5 min-vh-100 d-flex justify-content-between flex-column">
        <div class="d-flex flex-column justify-content-center">
            <from class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title">Nous contacter</h5>
                    <div class="input-group mb-3">
                        <label for="name" class="input-group-text">Nom</label>
                        <input type="text" class="form-control" id="name" placeholder="Entrez votre nom" minlength="2" maxlength="255" required
                        <?php
                            if (isset($result)) {
                                echo "value=\"" . $result["name"] . " " . $result["surname"] . "\"";
                            }
                        ?>>
                    </div>
                    <div class="input-group mb-3">
                        <label for="email" class="input-group-text">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Entrez votre email" required
                        <?php
                            if (isset($result)) {
                                echo "value=\"" . $result["email"] . "\"";
                            }
                        ?>>
                    </div>
                    <div class="input-group mb-3">
                        <label for="subject" class="input-group-text">Sujet</label>
                        <input type="text" class="form-control" id="subject" placeholder="Sujet du message" maxlength="100" minlength="2" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" rows="4" placeholder="Votre message" maxlength="200" minlength="2" required></textarea>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-success m-2" type="submit">
                            Envoyer
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>
                <div id="alertMessage" class="mt-3"></div>
            </form>
        </div>
    </main>
    <include href="templates/footer.html"></include>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
