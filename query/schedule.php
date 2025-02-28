<?php
    include "../session_utils.php";

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        exit;
    }

    header('Content-Type: application/json');

    $pdo = connectDB();

    if (isset($_GET['available']) &&
        isset($_GET['beg-date']) && validate_date($_GET['beg-date']) &&
        isset($_GET['beg-time']) && validate_time($_GET['beg-time']) &&
        isset($_GET['end-date']) && validate_date($_GET['end-date']) &&
        isset($_GET['end-time']) && validate_time($_GET['end-time'])) {

        $begDate = $_GET['beg-date'];
        $begTime = $_GET['beg-time'];
        $endDate = $_GET['end-date'];
        $endTime = $_GET['end-time'];

        $stmt = $pdo->prepare("SELECT beg_date, beg_time, end_date, end_time FROM AR_DT WHERE (beg_date >= ? AND beg_time >= ? AND end_date <= ? AND end_time <= ?)");
        $stmt->execute([$begDate, $begTime, $endDate, $endTime]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $availableSlots = [];

        // Define the working hours (excluding weekends)
        $workingHours = [
            'morning' => ['start' => '09:00:00', 'end' => '12:00:00'],
            'afternoon' => ['start' => '13:00:00', 'end' => '16:00:00']
        ];

        // Loop through the days between begDate and endDate
        $currentDate = $begDate;
        $endingTime = strtotime($endDate);
        while (strtotime($currentDate) <= $endingTime) {
            // If it's a weekend, skip it
            if (isWeekend($currentDate)) {
                $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                continue;
            }

            // Generate morning and afternoon slots
            foreach ($workingHours as $period => $times) {
                $startTime = $times['start'];
                $endTime = $times['end'];
        
                // Generate time slots from start to end time (1-hour intervals)
                $start = strtotime("$currentDate $startTime");
                $end = strtotime("$currentDate $endTime");

                while ($start < $end) {
                    // Create the current slot (1-hour increment)
                    $slotStartDate = date('Y-m-d', $start); // Separate date part
                    $slotStartTime = date('H:i:s', $start); // Separate time part
                    $slotEndDate = date('Y-m-d', $start + 3600); // Separate date part for end
                    $slotEndTime = date('H:i:s', $start + 3600); // Separate time part for end

                    // Check if the slot is already booked
                    $isBooked = false;
                    foreach ($result as $booking) {
                        if (($slotStartDate == $booking['beg_date'] && $slotStartTime >= $booking['beg_time'] && $slotStartTime < $booking['end_time']) ||
                            ($slotEndDate == $booking['beg_date'] && $slotEndTime > $booking['beg_time'] && $slotEndTime <= $booking['end_time'])) {
                            $isBooked = true;
                            break;
                        }
                    }

                    // If the slot is not booked, add it to the available slots
                    if (!$isBooked) {
                        $availableSlots[] = [
                            'beg_date' => $slotStartDate,
                            'beg_time' => $slotStartTime,
                            'end_date' => $slotEndDate,
                            'end_time' => $slotEndTime
                        ];
                    }

                    // Move to the next slot (1 hour later)
                    $start += 3600;
                }
            }

            // Move to the next day
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        echo json_encode($availableSlots);
    } else if (isset($_GET['user']) &&
        isset($_GET['beg-date']) && validate_date($_GET['beg-date']) &&
        isset($_GET['beg-time']) && validate_time($_GET['beg-time']) &&
        isset($_GET['end-date']) && validate_date($_GET['end-date']) &&
        isset($_GET['end-time']) && validate_time($_GET['end-time'])) {

        $begDate = $_GET['beg-date'];
        $begTime = $_GET['beg-time'];
        $endDate = $_GET['end-date'];
        $endTime = $_GET['end-time'];

        $stmt = $pdo->prepare("SELECT id, beg_date, beg_time, end_date, end_time, msg FROM AR_DT WHERE (src = ? AND beg_date >= ? AND beg_time >= ? AND end_date <= ? AND end_time <= ?)");
        $stmt->execute([$_SESSION["id"], $begDate, $begTime, $endDate, $endTime]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
    } else if (isset($_GET['upcoming']) &&
        isset($_GET['beg-date']) && validate_date($_GET['beg-date']) &&
        isset($_GET['beg-time']) && validate_time($_GET['beg-time'])) {

        $begDate = $_GET['beg-date'];
        $begTime = $_GET['beg-time'];

        $stmt = $pdo->prepare("SELECT id, beg_date, beg_time, end_date, end_time, msg FROM AR_DT WHERE (src = ? AND beg_date >= ? AND beg_time >= ?) LIMIT 5");
        $stmt->execute([$_SESSION["id"], $begDate, $begTime]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($result);
    }
?>
