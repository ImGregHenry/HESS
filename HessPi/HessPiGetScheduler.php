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


    $percent = 0;
    $url = "http://hess.site88.net/HessCloudGetScheduler.php";
    
    $ch = curl_init();
        
    $timestamp = DATE(DB_DATE_FORMAT, TIME());
    //$timestampMS = round(microtime(true) * 1000);
        
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_URL, $url);
        
    # Send request.
    $result = curl_exec($ch);
    //echo $result;
    curl_close($ch);
    #echo "TIME SENT : " . $timestamp . "\n";
    # Print response.
    # echo "<pre>$result</pre>";
        

    $schedule = json_decode($result);
    
    
    $deviceID = PI_DEVICE_ID;


    // Erase previous entries from database.
    PiStateTracker::deleteAllSchedulesFromDB();

    $isRunOnce = false;
    foreach($schedule->Schedule as $item) { 

        // if(!$isRunOnce) {
        //     $isNewSchedule = IsNewPiSchedule($item->PeakScheduleID);
        //     $isRunOnce = true;            
        // }

        // if($isNewSchedule || true) {
        
        $peakScheduleID = $item->PeakScheduleID;
        $weekTypeID = $item->WeekTypeID;
        $peakTypeID = $item->PeakTypeID;
        $startTime = $item->StartTime;
        $endTime = $item->EndTime;
        
        $currentDateTime = DATE(DB_DATE_FORMAT, TIME());
        
        try {
            
            $query = "INSERT INTO PeakSchedule (WeekTypeID, PeakTypeID, StartTime, EndTime) "
            . " VALUES (:weekTypeID, :peakTypeID, :startTime, :endTime)";
            
            $conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':weekTypeID', $weekTypeID, PDO::PARAM_INT);
            $stmt->bindParam(':peakTypeID', $peakTypeID, PDO::PARAM_INT);
            //$stmt->bindParam(':deviceID', $deviceID, PDO::PARAM_INT);
            $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
            $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);

            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo "ERROR: $e";
        }
        $cronScheduler = new CronJobScheduler();
        $cronScheduler::deleteAllCronJobs();

        $cronScheduler::createDefaultHessCronJobs();
        $cronScheduler::createBatterySchedulingCronJob($item->StartTime, $item->EndTime, $item->PeakTypeID);

        // } else {
        //     echo "OLD SCHEDULE \n\n";
        // }
        
        //TODO: set current peak type   
    }

?>
