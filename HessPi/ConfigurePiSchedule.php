<?php
    include_once 'HessGlobals.php';
    include_once 'HessPiStateTracker.php';    

    $batteryStatusLevel = PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);
    $peakScheduleID = PiStateTracker::getCurrentPeakType();
    $isInverterOn = PiStateTracker::isInverterStateOn();
    $isInit = false;

    echo "CONFIGURE SCHEDULE!  Battery: " . $batteryStatusLevel 
    . ", PeakSchID: " . $peakScheduleID . ", isInvertOn: " . $isInverterOn . ", isInit: " . $isInit;

    PiStateTracker::setPiSystemState($peakScheduleID, $batteryStatusLevel, $isInit, $isInverterOn);

?>
