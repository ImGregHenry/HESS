<?php
// HESS GLOBALS FILE

// USE THIS INCLUDE SCRIPT TO USE THIS FILE:
// include_once("HessGlobals.php");

# PI CONTROL SCRIPTS
define('PISCRIPT_PATH', '/usr/pi/scripts/');
define('PISCRIPT_INVERTER_ON', 'InverterOn.py');
define('PISCRIPT_INVERTER_OFF', 'InverterOff.py');
define('PISCRIPT_ACFROMWALL_ON', 'ACFromWallOn.py');
define('PISCRIPT_ACFROMWALL_OFF', 'ACFromWallOff.py');
define('PISCRIPT_BATTERYCHARGER_ON', 'BatteryChargerOn.py');
define('PISCRIPT_BATTERYCHARGER_OFF', 'BatteryChargerOff.py');

# PEAK TYPES
define('PEAKTYPE_OFF', '1');
define('PEAKTYPE_ON', '2');
define('PEAKTYPE_MID', '3');

# CLOUD MY SQL CREDENTIALS
define('MYSQL_CLOUD_HOST', 'mysql1.000webhost.com');
define('MYSQL_CLOUD_DATABASE', 'a4060350_HESS');
define('MYSQL_CLOUD_USER', 'a4060350_HESSADM');
define('MYSQL_CLOUD_PASSWORD', 'HessCloud1');

# PI MY SQL CREDENTIALS
define('MYSQL_PI_HOST', 'localhost');
define('MYSQL_PI_DATABASE', 'HESS');
define('MYSQL_PI_USER', 'root');
define('MYSQL_PI_PASSWORD', 'password');

# SET MYSQL DATE FORMAT
define('DB_DATE_FORMAT', 'Y-m-d H:i:s');

# SET DEFAULT TIME ZONES
date_default_timezone_set('US/Eastern');


?>