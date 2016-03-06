<?php
    include 'HessGlobals.php';
    
    $result = json_decode(file_get_contents("php://input"));
    
    foreach($result->Schedule as $item) {
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
            
            $conn = new PDO("mysql:host=MYSQL_CLOUD_HOST;dbname=MYSQL_CLOUD_DATABASE", MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
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