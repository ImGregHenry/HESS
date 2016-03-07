<?php
    include_once 'HessGlobals.php';
    include_once 'HessPiCronJobScheduler.php';

function IsNewPiSchedule($peakID) {

    try {        
        $query = "SELECT * FROM PeakSchedule WHERE PeakScheduleID = :peakScheduleID";
        echo "peakid = " . $peakID . "\n\n";
        $conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':peakScheduleID', $peakID, PDO::PARAM_INT);
        
        $stmt->execute();

        if ($stmt->rowCount() > 0)
            return false;
        else
            return true;
    }
    catch(PDOException $e) {
        echo "ERROR: $e";
    }
    
    return false;
}


    # Set time zone to get proper time
    date_default_timezone_set('US/Eastern');
    $DATE_FORMAT = "Y-m-d H:i:s";
    $percent = 0;
    $url = "http://hess.site88.net/HessCloudGetScheduler.php";
    
    $ch = curl_init();
        
    $timestamp = DATE($DATE_FORMAT, TIME());
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


    $isRunOnce = false;
    foreach($schedule->Schedule as $item) { 

        if(!$isRunOnce) {
            $isNewSchedule = IsNewPiSchedule($item->PeakScheduleID);
            $isRunOnce = true;            
        }



        if($isNewSchedule || true) {
            echo "NEW SCHEDULE";
            $peakScheduleID = $item->PeakScheduleID;
            $weekTypeID = $item->WeekTypeID;
            $peakTypeID = $item->PeakTypeID;
            $startTime = $item->StartTime;
            $endTime = $item->EndTime;
            
            $currentDateTime = DATE(DB_DATE_FORMAT, TIME());
            
            try {
                
                $query = "INSERT INTO PeakSchedule (PeakScheduleID, WeekTypeID, PeakTypeID, StartTime, EndTime) "
                . "VALUES (:peakScheduleID, :weekTypeID, :peakTypeID, :startTime, :endTime)";
                
                $conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_STR, 20);
                $stmt->bindParam(':weekTypeID', $weekTypeID, PDO::PARAM_INT);
                $stmt->bindParam(':peakTypeID', $peakTypeID, PDO::PARAM_INT);
                //$stmt->bindParam(':deviceID', $deviceID, PDO::PARAM_INT);
                $stmt->bindParam(':startTime', date("H:i:s", strtotime($startTime)), PDO::PARAM_STR);
                $stmt->bindParam(':endTime', date("H:i:s", strtotime($endTime)), PDO::PARAM_STR);

                
                //echo "START:::: " . date("H:i:s", strtotime($startTime)) . " ... " . $startTime. "  \n\n";
                //echo "END:::: " . date("H:i:s", strtotime($endTime)) . " ... " . $endTime . " \n\n";

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

        } else {
            echo "OLD SCHEDULE \n\n";
        }
        
        //TODO: set current peak type   
    }

?>
