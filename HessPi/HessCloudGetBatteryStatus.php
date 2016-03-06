<?php
	include 'HessGlobals.php';

	$deviceID = 19;
	
	try {
		
		$query = "SELECT DeviceID, PeakScheduleID, IsEnabled, RecordTime, CloudRecordTime, PowerLevelValue, PowerLevelPercent FROM BatteryStatus "
			. "WHERE DeviceID = :deviceID "
                        . "SORT BY RecordTime DESC "
                        . "LIMIT 1";
		
		$conn = new PDO("mysql:host=MYSQL_CLOUD_HOST;dbname=MYSQL_CLOUD_DATABASE", MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':deviceID', PI_DEVICE_ID, PDO::PARAM_INT);
		$stmt->execute();
		
		
		# Results array
		$BatteryStatus = array("BatteryStatus" => array());
		
		if ($stmt->rowCount() > 0) {
			
			# Loop over reach row
		    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
							
				# Create array from the current row
				$temparray = 	array('PeakScheduleID' => $rows['PeakScheduleID'],
							'DeviceID' => $rows['DeviceID'],
							'RecordTime' => $rows['RecordTime'],
							'IsEnabled' => $rows['IsEnabled'],
							'CloudRecordTime' => $rows['CloudRecordTime'],
							'PowerLevelValue' => $rows['PowerLevelValue'],
							'PowerLevelPercent' => $rows['PowerLevelPercent']);
				
				# Push the current row array into the results array
				array_push($BatteryStatus['BatteryStatus'], $temparray); 
			}
		}
	}
	catch(PDOException $e)
	{
		echo "ERROR: $e";
        return;
	}
	//var_dump($BatteryStatus);
	//echo "\n\n";
	//var_dump(json_encode($BatteryStatus));
	
	echo json_encode($BatteryStatus);
		
?>
