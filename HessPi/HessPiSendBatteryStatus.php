<html>
<body>
<p></p>
<?php
    # Set time zone to get proper time
	date_default_timezone_set('US/Eastern');
	$DATE_FORMAT = "Y-m-d H:i:s";
	$percent = 0;
	$MAX_MESSAGES = 10;
	$url = "http://hess.site88.net/HessPutBatteryStatus.php";
    
	
	for($i = 0; $i < $MAX_MESSAGES;  $i++) {
			
		$ch = curl_init( $url );
		
		$timestamp = DATE($DATE_FORMAT, TIME());
		$timestampMS = round(microtime(true) * 1000);
		
		# Setup request to send json via POST.
		$jsonme = array(
						"BatteryStatus" => array(
												 array(
													   'PeakScheduleID' => 1,
													   'IsEnabled' => 1,
													   'PowerLevelValue' => 3.8,
													   'PowerLevelPercent' => $percent++,
													   'RecordTime' => $timestamp, 
													   'RecordTimeMS' => $timestampMS,
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
		echo "TIME SENT : " . $timestamp . "\n"; 
		sleep(3);
		# Print response.
		# echo "<pre>$result</pre>";
	}
?>
</body>
</html>