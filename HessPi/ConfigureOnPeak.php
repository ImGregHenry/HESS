<?php
 
    exec('sudo python /pythonscripts/ACFromWallOff.py');
    sleep(1);
    exec('sudo python /pythonscripts/BatteryChargerOff.py');
    sleep(1);
    exec('sudo python /pythonscripts/InverterToggle.py');
    sleep(1);
?>