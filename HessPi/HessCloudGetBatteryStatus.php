<?php
	$mysql_host = "mysql1.000webhost.com";
	$mysql_database = "a4060350_HESS";
	$mysql_user = "a4060350_HESSADM";
	$mysql_password = "HessCloud1";


	$deviceID = 19;
	
	try {
		
		$query = "SELECT DeviceID, PeakScheduleID, IsEnabled, RecordTime, CloudRecordTime, PowerLevelValue, PowerLevelPercent FROM BatteryStatus "
			. "WHERE DeviceID = :deviceID "
                        . "SORT BY RecordTime DESC "
                        . "LIMIT 1";
		
		$conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':deviceID', $deviceID, PDO::PARAM_INT);
		$stmt->execute();
		
		
		// Results array
		$BatteryStatus = array("BatteryStatus" => array());
		
		if ($stmt->rowCount() > 0) {
			
			// Loop over reach row
		    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
							
				// Create array from the current row
				$temparray = 	array('PeakScheduleID' => $rows['PeakScheduleID'],
							'DeviceID' => $rows['DeviceID'],
							'RecordTime' => $rows['RecordTime'],
							'IsEnabled' => $rows['IsEnabled'],
							'CloudRecordTime' => $rows['CloudRecordTime'],
							'PowerLevelValue' => $rows['PowerLevelValue'],
							'PowerLevelPercent' => $rows['PowerLevelPercent']);
				
				// Push the current row array into the results array
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