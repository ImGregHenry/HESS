<?php
    # Set time zone to get proper time
	date_default_timezone_set('US/Eastern');
	$DATE_FORMAT = "Y-m-d H:i:s";
	$timestamp = DATE($DATE_FORMAT, TIME());
	

	//TODO: get power usage value from python script
	$watts = 1.552;
	
	$url = "http://hess.site88.net/HessCloudPutPowerUsage.php";
    
	$ch = curl_init( $url );
	
	# Setup request to send json via POST.
	$jsonme = array(
					"BatteryStatus" => array(
											 array(
												   'RecordTime' => $timestamp,
												   'PowerUsageInWatts' => $watts
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

	echo "<pre>[HessPiSendPowerUsage] JSON Package Sent: " . var_dump($payload); . "</pre>";
	//sleep(3);
	//}



?>



