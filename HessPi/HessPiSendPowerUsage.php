<html>
<body>
<p></p>
<?php
    # Set time zone to get proper time
	date_default_timezone_set('US/Eastern');
	$DATE_FORMAT = "Y-m-d H:i:s";
	
	$percent = 0;
	
	$url = "http://hess.site88.net/HessPutBatteryStatus.php";
    
	
	$ch = curl_init( $url );
	
	$timestamp = DATE($DATE_FORMAT, TIME());
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
</body>
</html>