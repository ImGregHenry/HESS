<?php
    
    $percent = 0;
    
    $BatteryStatus = array("BatteryStatus" => array());
    
    $url = "http://hess.site88.net/HessCloudPutBatteryStatus.php";
    
    $ch = curl_init( $url );
    
    //TODO: get the actual current PeakScheduleID
    
    # Set time zone to get proper time
    date_default_timezone_set('US/Eastern');
    $DATE_FORMAT = "Y-m-d H:i:s";
    $timestamp = DATE($DATE_FORMAT, TIME());
    //$timestampMS = round(microtime(true) * 1000);
    
    # Setup request to send json via POST.
    $temparray = array('PeakScheduleID' => 1,
                       'IsEnabled' => 1,
                       'PowerLevelPercent' => 80,
                       'RecordTime' => $timestamp);
    
    array_push($BatteryStatus['BatteryStatus'], $temparray);
    
    $payload = json_encode( $BatteryStatus );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    
    # Send request.
    $result = curl_exec($ch);
    echo $result;
    curl_close($ch);
    //echo "TIME SENT : " . $timestamp . "\n";
    
    echo "<pre>$payload<pre>";
    //sleep(3);
    //}
?>