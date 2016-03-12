<?php
    include_once 'HessGlobals.php';
   	include 'HessPiStateTracker.php';
    // exec('sudo python /pythonscripts/ACFromWallOff.py');
    // sleep(1);
    // exec('sudo python /pythonscripts/BatteryChargerOff.py');
    // sleep(1);
    // exec('sudo python /pythonscripts/InverterToggle.py');
    // sleep(1);


	$isInitialize = 1;
	$isInverterOn = 1;
	$isInverterOff = 0;
	$isChargerOn = 0;
	$isChargerOff = 1;
	$isACWallOn = 0;
	$isACWallOff = 0;

	insertStateTrackingEntry($isInitialize, $isInverterOn, $isInverterOff, $isChargerOn, $isChargerOff, $isACWallOn, $isACWallOff);
?>
