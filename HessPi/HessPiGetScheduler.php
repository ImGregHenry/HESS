<?php
    # Set time zone to get proper time
    date_default_timezone_set('US/Eastern');
    $DATE_FORMAT = "Y-m-d H:i:s";
    $percent = 0;
    $url = "http://hess.site88.net/HessCloudGetScheduler.php";

    
    $ch = curl_init();
        
    $timestamp = DATE($DATE_FORMAT, TIME());
    $timestampMS = round(microtime(true) * 1000);
        
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_URL, $url);
        
    # Send request.
    $result = curl_exec($ch);
    curl_close($ch);
    #echo "TIME SENT : " . $timestamp . "\n";
    # Print response.
    # echo "<pre>$result</pre>";
        
    $schedule = json_decode($result);
    
    foreach($schedule->Schedule as $item) {
        $peakScheduleID = $item->PeakScheduleID;
        $weekTypeID = $item->WeekTypeID;
        $peakTypeID = $item->PeakTypeID;
        $startTime = $item->StartTime;
        $endTime = $item->EndTime;
        
        date_default_timezone_set('US/Eastern');
        $DATE_FORMAT = "Y-m-d H:i:s";
        $currentDateTime = DATE($DATE_FORMAT, TIME());
        
        try {
            
            $query = "INSERT INTO PeakSchedule (PeakScheduleID, WeekTypeID, PeakTypeID, StartTime, EndTime) "
            . "VALUES (:peakScheduleID, :deviceID, :weekTypeID, :peakTypeID, :startTime, :endTime)";
            
            $conn = new PDO("mysql:host=MYSQL_PI_HOST;dbname=MYSQL_PI_DATABASE", MYSQL_PI_USER, MYSQL_PI_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_STR, 20);
            $stmt->bindParam(':weekTypeID', $weekTypeID, PDO::PARAM_INT);
            $stmt->bindParam(':peakTypeID', $peakTypeID, PDO::PARAM_INT);
            $stmt->bindParam(':startTime', date("H:i:s", $startTime), PDO::PARAM_STR);
            $stmt->bindParam(':endTime', date("H:i:s", $endTime), PDO::PARAM_STR);
            
            $stmt->execute();
            
        }
        catch(PDOException $e)
        {
            //echo "ERROR: $e";
            return;
        }
        
        
    }
?>