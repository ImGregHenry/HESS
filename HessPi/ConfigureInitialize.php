<?php
    include_once 'HessGlobals.php';
   	include_once 'HessPiStateTracker.php';
   	include_once 'HessPiCronJobScheduler.php';


	PiStateTracker::getCloudScheduleForPi();
    //$batteryStatusLevel = PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);
    $batteryStatusLevel = 0.5;
	$peakType = PiStateTracker::getCurrentPeakType();
	$isInverterOn = PiStateTracker::isInverterStateOn();
	$isInit = true;

	echo "CONFIGURE INITIALIZE!  Battery: " . $batteryStatusLevel 
	. ", PeakType: " . $peakType . ", isInvertOn: " . $isInverterOn . ", isInit: " . $isInit . PHP_EOL;
	PiStateTracker::setPiSystemState($peakType, $batteryStatusLevel, $isInit, $isInverterOn);


		
    // $cronScheduler = new CronJobScheduler();
    // $cronScheduler::deleteAllCronJobs();
    // $cronScheduler::createDefaultHessCronJobs();


	// $isInitialize = 1;
	// $isInverterOn = 1;
	// $isInverterOff = 0;
	// $isChargerOn = 0;
	// $isChargerOff = 1;
	// $isACWallOn = 0;
	// $isACWallOff = 0;

	// PiStateTracker::insertStateTrackingEntry($isInitialize, $isInverterOn, $isInverterOff, $isChargerOn, $isChargerOff, $isACWallOn, $isACWallOff);

?>
