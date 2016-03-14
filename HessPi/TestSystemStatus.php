<?php
	include_once 'HessGlobals.php';
	include_once 'HessPiStateTracker.php';

    $batteryStatusLevel = PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);

	$peakScheduleID = PiStateTracker::getCurrentPeakType();
	echo "Peak: $peakScheduleID. ";
	
	$schedule = PiStateTracker::getPeakSchedule();
	echo "INVERTER STATE: $schedule";



?>
