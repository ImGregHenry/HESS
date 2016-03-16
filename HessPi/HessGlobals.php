<?php
// HESS GLOBALS FILE

// USE THIS INCLUDE SCRIPT TO USE THIS FILE:
// include_once("HessGlobals.php");

# PI CONTROL SCRIPTS
define('PISCRIPT_PATH', 'usr/pi/scripts/');
define('PYTHON_EXEC_PATH', 'sudo python');
define('PISCRIPT_PYTHON_PATH', 'pythonscripts/');
define('PISCRIPT_BATTERY_PERCENT', 'GetBatteryPercentage.py');
define('PISCRIPT_POWER_USAGE', 'GetWattage.py');
define('PISCRIPT_INVERTER_TOGGLE', 'InverterToggle.py');
define('PISCRIPT_INVERTER_OFF', 'InverterToggle.py');
define('PISCRIPT_INVERTER_ON', 'InverterToggle.py');
define('PISCRIPT_ACFROMWALL_ON', 'ACFromWallOn.py');
define('PISCRIPT_ACFROMWALL_OFF', 'ACFromWallOff.py');
define('PISCRIPT_BATTERYCHARGER_ON', 'BatteryChargerOn.py');
define('PISCRIPT_BATTERYCHARGER_OFF', 'BatteryChargerOff.py');
define('PISCRIPT_CHECK_SYSTEM_STATUS', 'GetSystemStatus.py');
define('PISCRIPT_SCHEDULER_OFF_PEAK', 'ConfigureOffPeak.php');
define('PISCRIPT_SCHEDULER_ON_PEAK', 'ConfigureOnPeak.php');
define('PISCRIPT_SCHEDULER_MID_PEAK', 'ConfigureMidPeak.php');
define('PISCRIPT_CONFIG_PI_STATE', 'ConfigurePiSchedule.php');


# PEAK TYPES
define('PEAKTYPE_OFF', '1');
define('PEAKTYPE_ON', '2');
define('PEAKTYPE_MID_ENABLE', '3');
define('PEAKTYPE_MID_DISABLE', '4');

define('PI_DEVICE_ID', 19);

# CLOUD MY SQL CREDENTIALS
define('MYSQL_CLOUD_HOST', 'mysql1.000webhost.com');
define('MYSQL_CLOUD_DATABASE', 'a4060350_HESS');
define('MYSQL_CLOUD_USER', 'a4060350_HESSADM');
define('MYSQL_CLOUD_PASSWORD', 'HessCloud1');
define('MYSQL_CLOUD_DEBUG_HOST', '127.0.0.1');

# PI MY SQL CREDENTIALS
define('MYSQL_PI_HOST', 'localhost');
define('MYSQL_PI_DATABASE', 'HESS');
define('MYSQL_PI_USER', 'HESSADM');
define('MYSQL_PI_PASSWORD', 'password');

# SET MYSQL DATE FORMAT
define('DB_DATE_FORMAT', 'Y-m-d H:i:s');
define('DB_TIME_FORMAT', 'H:i:s');

# SET DEFAULT TIME ZONES
date_default_timezone_set('US/Eastern');

# Folder paths
define('PI_PHP_EXEC_PATH', 'php');
define('PI_HESS_SCRIPTS_PATH', '/home/pi/PREDEMO/');

# Minimum battery levels
define('BATTERY_MIN_LEVEL', 0.20);
define('BATTERY_MAX_LEVEL', 0.90);
define('SYSTEM_OFFLINE_VAL', 0);
define('SYSTEM_ONLINE_VAL', 1);

define('DEBUG_FLAG', false);

?>