<?php
    include_once 'HessGlobals.php';
    
    $percent = 0;
    //TODO: get the actual current PeakScheduleID
    

    //$command = escapeshellcmd(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);
    //$percent = exec("sudo " . PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);

    $BatteryStatus = array("BatteryStatus" => array());
    
    $url = "http://hess.site88.net/HessCloudPutBatteryStatus.php";
    

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


$timestamp = DATE(DB_DATE_FORMAT);
//array_push($BatteryStatus['BatteryStatus'], $temparray);

$data = array('PeakScheduleID' => 1,
                   'IsEnabled' => 1,
                   'PowerLevelPercent' => 10,
                   'RecordTime' => $timestamp);

echo post_to_url($url, $data);


?>
