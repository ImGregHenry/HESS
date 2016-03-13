<?php
    include_once 'HessGlobals.php';

//TODO: make cURL GET/POST class
function post_to_url($url, $data) {
    $fields = '';
    foreach ($data as $key => $value) {
        $fields .= $key . '=' . $value . '&';
    }
    rtrim($fields, '&');

    $post = curl_init();

    curl_setopt($post, CURLOPT_URL, $url);
    curl_setopt($post, CURLOPT_POST, count($data));
    curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($post);

    curl_close($post);
    return $result;
}

    $timestamp = DATE(DB_DATE_FORMAT, TIME());

	//TODO: get power usage value from python script
	$watts = 1.552;
	
	$url = "http://hess.site88.net/HessCloudPutPowerUsage.php";
    
	$ch = curl_init( $url );
	
	# Setup request to send json via POST.
	$jsonme = 	 array(
					   'RecordTime' => $timestamp,
					   'PowerUsageWatt' => $watts,
                       'PeakTypeID' => PiStateTracker::getCurrentPeakType());
			
	echo post_to_url($url, $data);


	
	//echo "<pre>[HessPiSendPowerUsage] JSON Package Sent: " . var_dump($payload); . "</pre>";
	//sleep(3);
	//}



?>



