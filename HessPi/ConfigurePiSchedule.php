<?php
    include_once 'HessGlobals.php';
    include_once 'HessPiStateTracker.php';    

    $sysStatus = PiStateTracker::isSystemOnline();
    
    if($sysStatus == SYSTEM_ONLINE_VAL) {
    
        //$batteryStatusLevel = PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);
        $batteryStatusLevel = 0.5;

        $peakType = PiStateTracker::getCurrentPeakType();
        $isInverterOn = PiStateTracker::isInverterStateOn();
        $isInit = false;

        echo "CONFIGURE SCHEDULE!  Battery: " . $batteryStatusLevel 
        . ", PeakType: " . $peakType . ", isInvertOn: " . $isInverterOn . ", isInit: " . $isInit;

        PiStateTracker::setPiSystemState($peakType, $batteryStatusLevel, $isInit, $isInverterOn);
    } else {
        PiStateTracker::setSystemOffline();
    }


?>
