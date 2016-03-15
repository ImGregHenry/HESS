<?php
	include_once 'HessGlobals.php';
	include_once 'HessPiStateTracker.php';

    //$batteryStatusLevel = PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);
	$batteryStatusLevel = 0.5;
	$peakType = PiStateTracker::getCurrentPeakType();
	echo "Peak: $peakType. ";
	
	$isInvOn = PiStateTracker::isInverterStateOn();
	echo "INVERTER STATE: " . $isInvOn;


?>
