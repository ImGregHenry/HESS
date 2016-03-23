<?php

include 'HessGlobals.php';
         
# Get the POSTed data.  Remove bad formatting.  Parse json.
$rawjson = STRIPSLASHES($_POST['JSON']);
$json = json_decode(stripslashes($rawjson));

$count = 1;

# Insert each item into the database
# Use the MAX PeakScheduleID value for all entries
foreach($json as $item) {  
    
    $weekTypeID = $item->WeekTypeID;
    $peakTypeID = $item->PeakTypeID;
    $startTime = $item->StartTime;
    $endTime = $item->EndTime;
    $isDeleted = $item->IsDeleted;
    //$peakTypeID = 2;
    //$weekTypeID = 2;
    //$startTime = DATE(DB_DATE_FORMAT, TIME());
    //$endTime = DATE(DB_DATE_FORMAT, TIME());
    
    # ID exists.  DELETE existing schedule.
    if($isDeleted && $item->PeakScheduleID) {
        $peakScheduleID = $item->PeakScheduleID;
        echo "DEL ID: (" . $count . "): " . $item->PeakScheduleID . ". ";

        $query = "DELETE FROM PeakSchedule"
            . " WHERE PeakScheduleID = :peakScheduleID;";
        
        $conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_INT);
        
        $stmt->execute();
    }
    # ID doesn't exist.  UPDATE existing schedule.# 
    else if($item->PeakScheduleID) {
        echo "UPD ID: (" . $count . "), ";
        $peakScheduleID = $item->PeakScheduleID;

        try {   
            $query = "UPDATE PeakSchedule"
            . " SET WeekTypeID = :weekTypeID,"
            . " PeakTypeID = :peakTypeID,"
            . " StartTime = :startTime,"
            . " EndTime = :endTime"
            . " WHERE PeakScheduleID = :peakScheduleID;";
            
            $conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_INT);
            $stmt->bindParam(':weekTypeID', $weekTypeID, PDO::PARAM_INT);
            $stmt->bindParam(':peakTypeID', $peakTypeID, PDO::PARAM_INT);
            $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
            $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);
            
            $stmt->execute();
            
        }
        catch(PDOException $e)
        {
            echo "ERROR: $e";
            return;
        }
    }
    # ID doesn't exist.  INSERT new schedule.
    else {
        try {
            $isUpdated = 0;
            echo "INS ID: (" . $count . ")";

            $query = "INSERT INTO PeakSchedule (WeekTypeID, PeakTypeID, StartTime, EndTime) "
            . "VALUES (:weekTypeID, :peakTypeID, :startTime, :endTime)";
            
            $conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':weekTypeID', $weekTypeID, PDO::PARAM_INT);
            $stmt->bindParam(':peakTypeID', $peakTypeID, PDO::PARAM_INT);
            $stmt->bindParam(':isUpdated', $isUpdated, PDO::PARAM_INT);
            $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
            $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);
            
            $stmt->execute();
        }
        catch(Exception $e) {
            echo "ERROR: $e";
        }
    }

    $count++;
}

 try {   
    $isUpdated = 1;
    $query = "UPDATE PeakSchedule"
    . " SET IsUpdated = :isUpdated;";
    
    $conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':isUpdated', $isUpdated, PDO::PARAM_INT);
    
    $stmt->execute();   
}
catch(PDOException $e)
{
    echo "ERROR: $e";
    return;
}

?>


