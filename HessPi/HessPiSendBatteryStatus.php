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


    $sysStatus = PiStateTracker::isSystemOnline();
    
    if($sysStatus == SYSTEM_ONLINE_VAL) {
        $peakType = PiStateTracker::getCurrentPeakType();
        $batteryStatusLevel = PiStateTracker::getBatteryStatusPercent($peakType);
        
        $isInverterOn = PiStateTracker::isInverterStateOn();
        $isInit = false;
$batteryStatusLevel2 = 0.5;
        if($batteryStatusLevel2 < BATTERY_MIN_LEVEL || $batteryStatusLevel2 > BATTERY_MAX_LEVEL) {
            //echo "CONFIGURE BATTERY STATE:!  Battery: " . $batteryStatusLevel . ", PeakSchID: " . $peakScheduleID 
              //  . ", isInvertOn: " . $isInverterOn . ", isInit: " . $isInit;
            
            PiStateTracker::setPiSystemState($peakType, $batteryStatusLevel, $isInit, $isInverterOn);
        }
    
        $url = "http://hess.site88.net/HessCloudPutBatteryStatus.php";    
        $timestamp = DATE(DB_DATE_FORMAT);
            
        $data = array('IsEnabled' => 1,
                       'PowerLevelPercent' => $batteryStatusLevel,
                       'RecordTime' => $timestamp);

        echo post_to_url($url, $data);
    } else {
        PiStateTracker::setSystemOffline();
    }

    

    

?>
