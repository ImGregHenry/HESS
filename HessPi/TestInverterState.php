<?php
include_once 'HessGlobals.php';
include_once 'HessPiStateTracker.php';

	//$peakScheduleID = PiStateTracker::getCurrentPeakType();
	//ECHO "peak: $peakScheduleID";
	
	$isInvOn = PiStateTracker::isInverterStateOn();
	echo "INVERTER STATE: " . $$isInvOn;
?>
