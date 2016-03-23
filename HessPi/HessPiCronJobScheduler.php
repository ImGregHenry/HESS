<?php
include_once 'HessGlobals.php';
# DATE FORMAT: http://php.net/manual/en/function.date.php

# ** CRON JOB USAGE ** 

# MIN HOUR DOM MON DOW CMD

#MIN	Minute field	0 to 59
#HOUR	Hour field	0 to 23
#DOM	Day of Month	1-31
#MON	Month field	1-12
#DOW	Day Of Week	0-6
#CMD	Command	Any command to be executed.


class CronJobScheduler {
	
	private static $cronArray = array();

	public static function createCronJobStringFromTimes($time) {
		$MIN =  INTVAL(DATE('i', strtotime($time)));
		$HOUR = DATE('G', strtotime($time));
		$DAY = "*";
		$MON = "*";
		$DOW = "*";
		
		return $MIN . " " . $HOUR . " " . $DAY . " " . $MON . " " . $DOW;
	}

	public static function createSingleCronJob($cronTimingText, $scriptName) {
		if(empty(CronJobScheduler::$cronArray)) {
			CronJobScheduler::$cronArray = array();
		}
		$ret = $cronTimingText . ' ' . PI_PHP_EXEC_PATH . ' ' . PI_HESS_SCRIPTS_PATH . $scriptName . PHP_EOL;
		array_push(CronJobScheduler::$cronArray, $ret);
		//echo 'CREATING JOB :' . $output . ' ' . $cronTimingText . ' ' . PI_PHP_EXEC_PATH . ' ' . PI_HESS_SCRIPTS_PATH . $scriptName . PHP_EOL;
	}

	public static function createAllCronJobs() {
		$output = shell_exec('crontab -l');
		$output = "";
		foreach (CronJobScheduler::$cronArray as $val) {
			if($val != null) 
				$output .= $val;
		}
		//$output .= "\r\n";
		//var_dump(CronJobScheduler::$cronArray);
		//echo "OUTPUT: " . $output;
		//$output = "0 21 * * * php /home/pi/PREDEMO/ConfigurePiSchedule.php" . PHP_EOL;
		file_put_contents('/tmp/crontab.txt',  $output);
		echo exec('crontab /tmp/crontab.txt');
	}

	public static function createDefaultHessCronJobs() {
		
		array_push(CronJobScheduler::$cronArray, CronJobScheduler::createSingleCronJob('*/1 * * * *', 'HessPiSendBatteryStatus.php'));
		array_push(CronJobScheduler::$cronArray, CronJobScheduler::createSingleCronJob('*/1 * * * *', 'HessPiGetScheduler.php'));
		array_push(CronJobScheduler::$cronArray, CronJobScheduler::createSingleCronJob('*/1 * * * *', 'HessPiSendPowerUsage.php'));
		//CronJobScheduler::createSingleCronJob('*/1 * * * * sleep 30;', 'HessPiSendPowerUsage.php');
	}

	public static function createOfflineHessCronJob() {
		array_push(CronJobScheduler::$cronArray, CronJobScheduler::createSingleCronJob('*/1 * * * *', 'ConfigureInitialize.php'));
	}

	public static function createBatterySchedulingCronJob($startTime, $endTime, $peakType) {
		$cronStartTimingString = CronJobScheduler::createCronJobStringFromTimes($startTime);
		$cronEndTimingString = CronJobScheduler::createCronJobStringFromTimes($endTime);

		echo "\nCRON START: " . $cronStartTimingString;
		echo "\nCRON: END:  " . $cronEndTimingString . "\n";
		
		array_push(CronJobScheduler::$cronArray, CronJobScheduler::createSingleCronJob($cronStartTimingString, PISCRIPT_CONFIG_PI_STATE));
		array_push(CronJobScheduler::$cronArray, CronJobScheduler::createSingleCronJob($cronEndTimingString, PISCRIPT_CONFIG_PI_STATE));
	}

	public static function deleteAllCronJobs() {
		$cronArray = array();
		echo exec('crontab -r');
	}
}

?>
