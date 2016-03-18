<?php
    include_once 'HessGlobals.php';
   	include_once 'HessPiStateTracker.php';
   	

   	$sysStatus = PiStateTracker::isSystemOnline();

   	echo "SYS: " . $sysStatus;
   	if($sysStatus == SYSTEM_ONLINE_VAL) {
		PiStateTracker::getCloudScheduleForPi();
	    $peakType = PiStateTracker::getCurrentPeakType();
	    $batteryStatusLevel = PiStateTracker::getBatteryStatusPercent($peakType);
	    
		$isInverterOn = PiStateTracker::isInverterStateOn();
		$isInit = true;

		echo "CONFIGURE INITIALIZE!  Battery: " . $batteryStatusLevel 
		. ", PeakType: " . $peakType . ", isInvertOn: " . $isInverterOn . ", isInit: " . $isInit . PHP_EOL;
		PiStateTracker::setPiSystemState($peakType, $batteryStatusLevel, $isInit, $isInverterOn);
	} else {
		PiStateTracker::setSystemOffline();
	}

?>
