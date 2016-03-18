<?php
	include 'HessGlobals.php';

	//TODO: limit the results by a date range or a parameter for number of results.
		
	try {
		
		$query = "SELECT PowerUsageID, PeakTypeID, RecordTime, PowerUsageWatt "
            . " FROM PowerUsage "
            . " ORDER BY PeakTypeID ASC, RecordTime ASC;";
        #. "ORDER BY RecordTime DESC "
        #. "LIMIT 100";
		
		$conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" .MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		
		# Results array
        $powerUsage = array("PowerUsage" => array());
		
		if ($stmt->rowCount() > 0) {
			
			# Loop over reach row
		    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
							
				# Create array from the current row
				$temparray = 	array('PowerUsageID' => $rows['PowerUsageID'],
                            'PeakTypeID' => $rows['PeakTypeID'],
							'RecordTime' => $rows['RecordTime'],
							'PowerUsageWatt' => $rows['PowerUsageWatt']);
				
				# Push the current row array into the results array
                array_push($powerUsage['PowerUsage'], $temparray);
			}
		}
	}
	catch(PDOException $e)
	{
		echo "ERROR: \r\n";
	    var_dump($e);
	}
	//var_dump($powerUsageData);
	//echo "\n\n";
	//var_dump(json_encode($powerUsageData));

	$payload = json_encode($powerUsage);
    echo $payload;
		
?>
