<?php
    
    echo "CONFIG OFF PEAK.  ACFromWallOn.\n";
    exec('sudo python pythonscripts/ACFromWallOn.py');
    sleep(1);
    echo "CONFIG OFF PEAK.  InverterToggle.\n";
    exec('sudo python pythonscripts/InverterToggle.py');
    sleep(1);
    echo "CONFIG OFF PEAK.  BatteryChargerOn.\n";
    exec('sudo python pythonscripts/BatteryChargerOn.py');
    sleep(1);
    echo "CONFIG OFF PEAK.  COMPLETE.";

    $isInitialize = 0;
	$isInverterOn = 0;
	$isInverterOff = 1;
	$isChargerOn = 1;
	$isChargerOff = 0;
	$isACWallOn = 1;
	$isACWallOff = 0;

	insertStateTrackingEntry($isInitialize, $isInverterOn, $isInverterOff, $isChargerOn, $isChargerOff, $isACWallOn, $isACWallOff);
?>
