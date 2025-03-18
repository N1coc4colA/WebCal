<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>WebCal - Mentions légales</title>
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

        if (isset($_SESSION["id"])) {
            echo "<include href=\"templates/header-connected.html\"></include>";
        } else {
            echo "<include href=\"templates/header.html\"></include>";
        }
    ?>
    <main class="container mt-2 pt-5 min-vh-100 d-flex justify-content-between flex-column">
        <div class="d-flex flex-column justify-content-center">

<h2>Mentions Légales</h2>

<h5>1. Éditeur du Site</h5>
<p>Le site WebCal (ci-après dénommé "le Site") est édité par : BADIN Nicolas</p>
<p>Nom de l’entreprise/personne physique : BADIN Nicolas</p>
<p>Email : webcal-contact.popsicle423@passmail.net</p>

<h5>2. Propriété Intellectuelle</h5>
<p>Tous les contenus présents sur le Site (textes, images, logos, vidéos, etc.) sont protégés par les lois en vigueur sur la propriété intellectuelle. Toute reproduction, représentation, modification ou exploitation totale ou partielle de ces éléments sans autorisation préalable est strictement interdite.

<h5>3. Données Personnelles</h5>
<p>Le traitement des données personnelles des utilisateurs respecte les dispositions du Règlement Général sur la Protection des Données (RGPD) et de la législation française en vigueur.</p>
<p>Responsable du traitement des données : BADIN Nicolas</p>
<p>Les données collectées via le Site sont utilisées pour :</p>

    <p>La gestion des comptes utilisateurs.</p>
    <p>La prise et la gestion des rendez-vous (fictifs).</p>
    <p>L’envoi de communications liées aux services proposés.</p>

<p>Les utilisateurs disposent d’un droit d’accès, de rectification, de suppression et de portabilité de leurs données, ainsi que d’un droit d’opposition ou de limitation au traitement. Ces droits peuvent être exercés en écrivant à : webcal-contact.popsicle423@passmail.net</p>

<h5>6. Responsabilité</h5>
<p>Le Site met tout en œuvre pour fournir des informations exactes et à jour, mais ne saurait être tenu responsable d’erreurs ou d’omissions.</p>

<h5>7. Loi Applicable et Juridiction</h5>
<p>Les présentes mentions légales sont régies par le droit français. En cas de litige, les tribunaux français seront seuls compétents.</p>
        </div>
    </main>
    <include href="templates/footer.html"></include>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>