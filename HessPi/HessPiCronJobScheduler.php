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

	public static function createBatterySchedulingCronJob($startTime, $endTime, $peakType) {
		$cronStartTimingString = CronJobScheduler::createCronJobStringFromTimes($startTime);
		$cronEndTimingString = CronJobScheduler::createCronJobStringFromTimes($endTime);
		

		echo "\nCRON START: " . $cronStartTimingString. "\n\n";
		echo "\nCRON: END:  " . $cronEndTimingString . "\n\n";
		if($peakType == PEAKTYPE_ON) {
			CronJobScheduler::createSingleCronJob($cronStartTimingString, PISCRIPT_SCHEDULER_ON_PEAK);
			// CronJobScheduler::createSingleCronJob($cronEndTimingString, PISCRIPT_SCHEDULER_OFF_PEAK);
		} else if($peakType == PEAKTYPE_MID) {
			CronJobScheduler::createSingleCronJob($cronStartTimingString, PISCRIPT_SCHEDULER_MID_PEAK);
			// CronJobScheduler::createSingleCronJob($cronEndTimingString, PISCRIPT_SCHEDULER_OFF_PEAK);
		} else {
			CronJobScheduler::createSingleCronJob($cronStartTimingString, PISCRIPT_SCHEDULER_OFF_PEAK);
		}
	}

	public static function deleteAllCronJobs() {
		echo exec('crontab -r');
	}
}

//$scheduler = new CronJobScheduler();
//$scheduler->deleteAllCronJobs();
//$scheduler->createSingleCronJob('*/2 * * * * php /home/pi/HessPiTestCron.php');

?>
