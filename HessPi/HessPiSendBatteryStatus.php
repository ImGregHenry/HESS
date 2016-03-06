<?php
    
	$percent = 0;
	
	$url = "http://hess.site88.net/HessPutBatteryStatus.php";
    
	$ch = curl_init( $url );
	
	//TODO: get the actual current PeakScheduleID

	$timestamp = DATE(DB_DATE_FORMAT, TIME());
	//$timestampMS = round(microtime(true) * 1000);
	
	# Setup request to send json via POST.
	$jsonme = array(
					"BatteryStatus" => array(
											 array(
												   'PeakScheduleID' => 1,
												   'IsEnabled' => 1,
												   'PowerLevelValue' => 3.8,
												   'PowerLevelPercent' => $percent++,
												   'RecordTime' => $timestamp, 
												   //'RecordTimeMS' => $timestampMS,
												   'DeviceID' => 19
												   )
											 )
					);
	
	$payload = json_encode( $jsonme );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	
	# Send request.
	$result = curl_exec($ch);
	curl_close($ch);
	//echo "TIME SENT : " . $timestamp . "\n"; 

	echo "<pre>JSON Package Sent: " . var_dump($payload); . "</pre>";
	//sleep(3);
	//}
?>
