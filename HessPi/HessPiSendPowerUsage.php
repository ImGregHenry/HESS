<?php
    include_once 'HessGlobals.php';
    include_once 'HessPiStateTracker.php';

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


   $sysStatus = PiStateTracker::isSystemOnline();
    
    if($sysStatus == SYSTEM_ONLINE_VAL) {
  
        $timestamp = DATE(DB_DATE_FORMAT, TIME());

        $watts = PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_POWER_USAGE);
    	
    	$url = "http://hess.site88.net/HessCloudPutPowerUsage.php";
        
    	$ch = curl_init( $url );
    	
    	# Setup request to send json via POST.
    	$jsonme = 	 array(
    					   'RecordTime' => $timestamp,
    					   'PowerUsageWatt' => $watts,
                           'PeakTypeID' => PiStateTracker::getCurrentPeakType());
    			
    	echo post_to_url($url, $data);
    } else {
        PiStateTracker::setSystemOffline();
    }
	
	//echo "<pre>[HessPiSendPowerUsage] JSON Package Sent: " . var_dump($payload); . "</pre>";
	//sleep(3);
	//}

?>

