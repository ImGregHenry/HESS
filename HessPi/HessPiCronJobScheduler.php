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

	public static function createCronJobStringFromTimes($time) {
		$MIN =  INTVAL(DATE('i', strtotime($time)));
		$HOUR = DATE('G', strtotime($time));
		$DAY = "*";
		$MON = "*";
		$DOW = "*";
		
		return $MIN . " " . $HOUR . " " . $DAY . " " . $MON . " " . $DOW;
	}

	public static function createSingleCronJob($cronTimingText, $scriptName) {
		$output = shell_exec('crontab -l');
		file_put_contents('/tmp/crontab.txt', $output . ' ' . $cronTimingText . ' ' . PI_PHP_EXEC_PATH . ' ' . PI_HESS_SCRIPTS_PATH . $scriptName . PHP_EOL);
		echo exec('crontab /tmp/crontab.txt');
		//echo 'CREATING JOB :' . $output . ' ' . $cronTimingText . ' ' . PI_PHP_EXEC_PATH . ' ' . PI_HESS_SCRIPTS_PATH . $scriptName . PHP_EOL;
	}

	public static function createDefaultHessCronJobs() {
		CronJobScheduler::createSingleCronJob('*/1 * * * *', 'HessPiSendBatteryStatus.php');
		CronJobScheduler::createSingleCronJob('*/1 * * * *', 'HessPiGetScheduler.php');
		//CronJobScheduler::createSingleCronJob('*/1 * * * *', 'HessPiSendPowerUsage.php');
		//CronJobScheduler::createSingleCronJob('*/1 * * * * sleep 30;', 'HessPiSendPowerUsage.php');
	}

	public static function createOfflineHessCronJob() {
		CronJobScheduler::createSingleCronJob('*/1 * * * *', 'ConfigureInitialize.php');
	}

	public static function createBatterySchedulingCronJob($startTime, $endTime, $peakType) {
		$cronStartTimingString = CronJobScheduler::createCronJobStringFromTimes($startTime);
		$cronEndTimingString = CronJobScheduler::createCronJobStringFromTimes($endTime);
		
		echo "\nCRON START: " . $cronStartTimingString. "\n";
		echo "\nCRON: END:  " . $cronEndTimingString . "\n\n";
		
		CronJobScheduler::createSingleCronJob($cronStartTimingString, PISCRIPT_CONFIG_PI_STATE);
		CronJobScheduler::createSingleCronJob($cronEndTimingString, PISCRIPT_CONFIG_PI_STATE);
	}

	public static function deleteAllCronJobs() {
		echo exec('crontab -r');
	}
}

//$scheduler = new CronJobScheduler();
//$scheduler->deleteAllCronJobs();
//$scheduler->createSingleCronJob('*/2 * * * * php /home/pi/HessPiTestCron.php');

?>
