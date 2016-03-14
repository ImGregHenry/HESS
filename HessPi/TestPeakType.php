<?php
include_once 'HessGlobals.php';
include_once 'HessPiStateTracker.php';

	$peakType = PiStateTracker::getCurrentPeakType();
	ECHO "PeakType: $peakType";
	
	$peakID = PiStateTracker::getPeakSchedule();
	echo "PEAK STATE: " . $peakID;
?>
