<?php

include 'HessGlobals.php';
         
# Get the POSTed data.  Remove bad formatting.  Parse json.
$rawjson = STRIPSLASHES($_POST['JSON']);
$json = json_decode(stripslashes($rawjson));

$count = 1;


try {
    $isUpdated = 1;
    $query2 = "UPDATE PeakSchedule SET IsUpdated = 1;";
    
    $conn2 = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
    $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt2 = $conn2->prepare($query2);
    
    $stmt2->execute();
}
catch(Exception $e)
{
    echo "ERROR: $e";
    return;
}

# Insert each item into the database
# Use the MAX PeakScheduleID value for all entries
foreach($json as $item) {  
    
    $weekTypeID = $item->WeekTypeID;
    $peakTypeID = $item->PeakTypeID;
    $startTime = $item->StartTime;
    $endTime = $item->EndTime;
    $isDeleted = $item->IsDeleted;

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
        $isUpdated = 1;

        try {   
            $query = "UPDATE PeakSchedule"
            . " SET WeekTypeID = :weekTypeID,"
            . " PeakTypeID = :peakTypeID,"
            . " StartTime = :startTime,"
            . " EndTime = :endTime,"
            . " IsUpdated = :isUpdated"
            . " WHERE PeakScheduleID = :peakScheduleID;";
            
            $conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_INT);
            $stmt->bindParam(':weekTypeID', $weekTypeID, PDO::PARAM_INT);
            $stmt->bindParam(':isUpdated', $isUpdated, PDO::PARAM_INT);
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
            $isUpdated = 1;
            echo "INS ID: (" . $count . ")";

            $query = "INSERT INTO PeakSchedule (WeekTypeID, PeakTypeID, StartTime, EndTime, IsUpdated) "
            . "VALUES (:weekTypeID, :peakTypeID, :startTime, :endTime, :isUpdated)";
            
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


?>


