<?php
	include_once 'HessGlobals.php';
	include_once 'HessPiStateTracker.php';

    
	$peakType = PiStateTracker::getCurrentPeakType();
	echo "Peak: $peakType. ";
	
	$isInvOn = PiStateTracker::isInverterStateOn();
	echo "INVERTER STATE: " . $isInvOn;
	
   	$sysStatus = PiStateTracker::isSystemOnline();
	echo "SYSTEM STATE: " . $sysStatus;

	$isAlreadyOffline = PiStateTracker::isSystemAlreadySetOffline();
	echo "IS ALREADY OFFLINE: " . $isAlreadyOffline;


	$val = PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);
	$val = 0.5;
    if($val > BATTERY_MIN_LEVEL && $val < BATTERY_MAX_LEVEL) 
    	echo "battery status level OK. ($val). ";
    else if ($val <= BATTERY_MIN_LEVEL)
    	echo "battery status level LOW. ($val)";
	else if ($val >= BATTERY_MAX_LEVEL)
    	echo "battery status level MAX. ($val)";

?>
