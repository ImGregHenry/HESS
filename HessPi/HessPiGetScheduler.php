<?php
    # Set time zone to get proper time
    date_default_timezone_set('US/Eastern');
    $DATE_FORMAT = "Y-m-d H:i:s";
    $percent = 0;
    $MAX_MESSAGES = 10;
    $url = "http://hess.site88.net/HessPiScheduler.php";
    
    
    for($i = 0; $i < $MAX_MESSAGES;  $i++) {
        
        $ch = curl_init();
        
        $timestamp = DATE($DATE_FORMAT, TIME());
        $timestampMS = round(microtime(true) * 1000);
        
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_URL, $url);
        
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        echo "TIME SENT : " . $timestamp . "\n"; 
        sleep(3);
        # Print response.
        # echo "<pre>$result</pre>";
    }
    ?>