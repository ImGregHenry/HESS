<?php
    
    echo "CONFIG ON PEAK.  BatteryChargerOff.\n";
    exec('python pythonscripts/BatteryChargerOff.py');
    sleep(1);
    echo "CONFIG ON PEAK.  InterterToggle.\n";
    exec('python pythonscripts/InverterToggle.py');
    sleep(35);
    echo "CONFIG ON PEAK.  ACFromWwallOff.\n";
    exec('python pythonscripts/ACFromWallOff.py');
    sleep(1);
    echo "CONFIG ON PEAK.  ACFromWwallOff.\n";
  
?>
