<?php
//TODO: determine if this is a new schedule
//TODO: set current peak type if it is a new schedule
include 'HessGlobals.php'
include 'HessPiCronJobScheduler.php'
	
	try {
		
		$query = "SELECT  PeakScheduleID, WeekTypeID, PeakTypeID, StartTime, EndTime"
			. " FROM PeakSchedule"
			. " ORDER BY PeakScheduleID DESC";
			. " LIMIT 1";
		
		$conn = new PDO("mysql:host=MYSQL_CLOUD_HOST;dbname=MYSQL_CLOUD_DATABASE", MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($query);
		$stmt->execute();
		
        # Results array
        $Schedule = array("Schedule" => array());
		
		if ($stmt->rowCount() > 0) {
			
			# Loop over reach row
			while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
							
				// Create array from the current row
                $temparray = array('PeakScheduleID' => $rows['PeakScheduleID'],
                                   'weekTypeID' => $rows['WeekTypeID'],
                                   'peakTypeID' => $rows['PeakTypeID'],
                                   'dbStartTime' => $rows['StartTime'],
                                   'dbEndTime' => $rows['EndTime']);
							
                // Push the current row array into the results array
                array_push($Schedule['Schedule'], $temparray);
		}
	}
    catch (Exception $e) {
		echo "ERROR: \r\n";
		var_dump($e);
	}
	
	
	//echo " THIS: " . $peakType;
    
    $payload = json_encode($Schedule);
?>
