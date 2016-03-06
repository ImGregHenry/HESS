<?php
    
    $percent = 0;
    
    $url = "http://hess.site88.net/HessCloudPutScheduler.php";
    
    $ch = curl_init( $url );
    
    //TODO: get the actual current PeakScheduleID
    
    $startTime = date("H:i:s", strtotime('08:00:00'));
    $endTime = date("H:i:s", strtotime('19:00:00'));
    //$timestampMS = round(microtime(true) * 1000);
    
    # Setup request to send json via POST.
    $peakSchedule = array(
                    "PeakSchedule" => array(
                                             array(
                                                   'PeakScheduleID' => 1,
                                                   'WeekTypeID' => 1,
                                                   'PeakTypeID' => 2,
                                                   'StartTime' => $startTime,
                                                   'EndTime' => $endTime
                                                   )
                                             )
                    );
    
    $payload = json_encode( $peakSchedule );
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