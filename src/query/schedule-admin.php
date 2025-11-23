<?php
    include "../session_utils.php";

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        exit;
    }

    header('Content-Type: application/json');

    if (isset($_GET['upcoming']) &&
        isset($_GET['beg-date']) && validate_date($_GET['beg-date']) &&
        isset($_GET['end-date']) && validate_date($_GET['end-date'])) {

        $begDate = $_GET['beg-date'];
        $endDate = $_GET['end-date'];
        $stmt = DB::getInstance()->prepare("SELECT id, beg_date, beg_time, end_date, end_time, msg FROM AR_DT WHERE (beg_date <= ? AND end_date >= ?);");
        $stmt->execute([$endDate, $begDate]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
    }
?>
