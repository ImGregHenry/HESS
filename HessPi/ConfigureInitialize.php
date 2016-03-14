<?php
    include_once 'HessGlobals.php';
   	include 'HessPiStateTracker.php';


    $batteryStatusLevel = PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);
	$peakScheduleID = PiStateTracker::getCurrentPeakType();
	$isInverterOn = PiStateTracker::isInverterStateOn();
	$isInit = true;

	echo "CONFIGURE INITIALIZE!  Battery: " . $batteryStatusLevel 
	. ", PeakSchID: " . $peakScheduleID . ", isInvertOn: " . $isInverterOn . ", isInit: " . $isInit;
	PiStateTracker::setPiSystemState($peakScheduleID, $batteryStatusLevel, $isInit, $isInverterOn);


	// $isInitialize = 1;
	// $isInverterOn = 1;
	// $isInverterOff = 0;
	// $isChargerOn = 0;
	// $isChargerOff = 1;
	// $isACWallOn = 0;
	// $isACWallOff = 0;

	// PiStateTracker::insertStateTrackingEntry($isInitialize, $isInverterOn, $isInverterOff, $isChargerOn, $isChargerOff, $isACWallOn, $isACWallOff);

?>
