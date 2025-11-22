<?php
    session_start();

    if (!isset($_SESSION["id"])  || is_null($_SESSION["id"])) {
        header("HTTP/2 404 Not Found");
        exit;
    }

    include "utils.php";

    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        header("HTTP/2 404 Not Found");
        exit;
    }

    if (!isset($_GET["eid"])) {
        header("HTTP/2 404 Not Found");
        exit;
    }

    try {
        DBAtomic::run(function(PDO $pdo) {
            $eid = (int) $_GET["eid"];
            $stmt = $pdo->prepare("DELETE FROM AR_DT WHERE (src=? AND id=?)");
            $stmt->execute([$_SESSION["id"], $eid]);
        });

        header("HTTP/2 200 OK");
    } catch (Exception $e) {
        header("HTTP/2 404 Not Found");
    }
?>
