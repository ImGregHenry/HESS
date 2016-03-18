<?php
    include_once 'HessGlobals.php';
    include_once 'HessPiStateTracker.php';    

    $sysStatus = PiStateTracker::isSystemOnline();
    
    if($sysStatus == SYSTEM_ONLINE_VAL) {
        $peakType = PiStateTracker::getCurrentPeakType();
        $batteryStatusLevel = PiStateTracker::getBatteryStatusPercent($peakType);
        //$batteryStatusLevel = 0.5;

        $isInverterOn = PiStateTracker::isInverterStateOn();
        $isInit = false;

        echo "CONFIGURE SCHEDULE!  Battery: " . $batteryStatusLevel 
        . ", PeakType: " . $peakType . ", isInvertOn: " . $isInverterOn . ", isInit: " . $isInit;

        PiStateTracker::setPiSystemState($peakType, $batteryStatusLevel, $isInit, $isInverterOn);
    } else {
        PiStateTracker::setSystemOffline();
    }


?>
