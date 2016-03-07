<?php
    
    exec('sudo python /pythonscripts/ACFromWallOn.py');
    sleep(1);
    exec('sudo python /pythonscripts/BatteryChargerOn.py');
    sleep(1);
    exec('sudo python /pythonscripts/InverterToggle.py');
    sleep(1);
?>