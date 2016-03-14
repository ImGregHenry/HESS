<?php
    include_once 'HessGlobals.php';
    include_once 'HessPiStateTracker.php';


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


    $batteryStatusLevel = PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);
    $isInverterOn = PiStateTracker::isInverterStateOn();
    $isInit = false;


    //echo "CONFIGURE INITIALIZE!  Battery: " . $batteryStatusLevel 
    //. ", PeakSchID: " . $peakScheduleID . ", isInvertOn: " . $isInverterOn . ", isInit: " . $isInit;
    //PiStateTracker::setPiSystemState($peakScheduleID, $batteryStatusLevel, $isInit, $isInverterOn);
    

    $url = "http://hess.site88.net/HessCloudPutBatteryStatus.php";
    //$BatteryStatus = array("BatteryStatus" => array());    

    $timestamp = DATE(DB_DATE_FORMAT);
    //array_push($BatteryStatus['BatteryStatus'], $temparray);

    //TODO: remove PeakScheduleID'
    $data = array('IsEnabled' => 1,
                   'PowerLevelPercent' => $batteryStatusLevel,
                   'RecordTime' => $timestamp);

    echo post_to_url($url, $data);


?>
