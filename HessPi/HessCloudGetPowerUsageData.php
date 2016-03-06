<?php
	include 'HessGlobals.php';

	//TODO: limit the results by a date range or a parameter for number of results.
		
	try {
		
		$query = "SELECT PowerUsageID, RecordTime, PowerUsageInWatts FROM PowerUsageData "
	                . "SORT BY RecordTime DESC "
	                . "LIMIT 100";
		
		$conn = new PDO("mysql:host=MYSQL_CLOUD_HOST;dbname=MYSQL_CLOUD_DATABASE", MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		
		# Results array
		$powerUsageData = array("PowerUsageData" => array());
		
		if ($stmt->rowCount() > 0) {
			
			# Loop over reach row
		    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
							
				# Create array from the current row
				$temparray = 	array('PowerUsageID' => $rows['PowerUsageID'],
							'RecordTime' => $rows['RecordTime'],
							'PowerUsageInWatts' => $rows['PowerUsageInWatts']);
				

				# Push the current row array into the results array
				array_push($powerUsageData['PowerUsageData'], $temparray); 
			}
		}
	}
	catch(PDOException $e)
	{
		echo "ERROR: $e";
	    return;
	}
	//var_dump($powerUsageData);
	//echo "\n\n";
	//var_dump(json_encode($powerUsageData));

	echo json_encode($powerUsageData);
		
?>
