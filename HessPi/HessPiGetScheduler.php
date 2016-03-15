<?php
    include_once 'HessGlobals.php';
    include_once 'HessPiCronJobScheduler.php';
    include_once 'HessPiStateTracker.php';    
// function IsNewPiSchedule($peakID) {

//     try {        
//         $query = "SELECT * FROM PeakSchedule WHERE PeakScheduleID = :peakScheduleID";
//         echo "peakid = " . $peakID . "\n\n";
//         $conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
//         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
//         $stmt = $conn->prepare($query);
//         $stmt->bindParam(':peakScheduleID', $peakID, PDO::PARAM_INT);
        
//         $stmt->execute();

//         if ($stmt->rowCount() > 0)
//             return false;
//         else
//             return true;
//     }
//     catch(PDOException $e) {
//         echo "ERROR: $e";
//     }
    
//     return false;
// }

    PiStateTracker::getCloudScheduleForPi();


?>
