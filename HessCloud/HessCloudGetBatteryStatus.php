<?php
	include_once 'HessGlobals.php';

	try {
		
		$query = "SELECT PeakScheduleID, IsEnabled, RecordTime, PowerLevelPercent "
			. " FROM BatteryStatus"
        	. " WHERE BatteryStatusID = (SELECT MAX(BatteryStatusID) FROM BatteryStatus);";
			#. " WHERE DeviceID = :deviceID "
            #. " ORDER BY RecordTime DESC ";
		
		$conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" .MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($query);
		#$stmt->bindParam(':deviceID', $devID=PI_DEVICE_ID, PDO::PARAM_INT);
		$stmt->execute();
		
		# Results array
		$BatteryStatus = array("BatteryStatus" => array());
		
		if ($stmt->rowCount() > 0) {
			
			# Loop over reach row
		    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
							
				# Create array from the current row
				$temparray = 	array('PeakScheduleID' => $rows['PeakScheduleID'],
							'RecordTime' => $rows['RecordTime'],
							'IsEnabled' => $rows['IsEnabled'],
							'PowerLevelPercent' => $rows['PowerLevelPercent']);
				
				# Push the current row array into the results array
//				array_push($BatteryStatus['BatteryStatus'], $temparray); 
			}
		}
	}
	catch(PDOException $e)
	{
		echo "ERROR: \r\n";
        var_dump($e);
	}
	//var_dump($BatteryStatus);
	//echo "\n\n";
	//var_dump(json_encode($BatteryStatus));
	
	
    $payload = json_encode($temparray);
    echo $payload;
		
?>
