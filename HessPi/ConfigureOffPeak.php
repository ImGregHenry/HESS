<?php
    
    echo "CONFIG OFF PEAK.  ACFromWallOn.\n";
    exec('sudo python pythonscripts/ACFromWallOn.py');
    sleep(1);
    echo "CONFIG OFF PEAK.  InverterToggle.\n";
    exec('sudo python pythonscripts/InverterToggle.py');
    sleep(1);
    echo "CONFIG OFF PEAK.  BatteryChargerOn.\n";
    exec('sudo python pythonscripts/BatteryChargerOn.py');
    sleep(1);
    echo "CONFIG OFF PEAK.  COMPLETE.";
?>
