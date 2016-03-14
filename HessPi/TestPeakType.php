<?php
include_once 'HessGlobals.php';
include_once 'HessPiStateTracker.php';

	//$peakScheduleID = PiStateTracker::getCurrentPeakType();
	//ECHO "peak: $peakScheduleID";
	
	$schedule = PiStateTracker::getPeakSchedule();
	echo "INVERTER STATE: " . $schedule;
?>
