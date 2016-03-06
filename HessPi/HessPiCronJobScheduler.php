<?php


class CronJobScheduler {

	
	function createSingleCronJob($cronText) {
		$output = shell_exec('crontab -l');
		file_put_contents('/tmp/crontab.txt', $output . $cronText . PHP_EOL);
		echo exec('crontab /tmp/crontab.txt');
	}

	function deleteAllCronJobs() {
		echo exec('crontab -r');
	}

}

$scheduler = new CronJobScheduler();
$scheduler->deleteAllCronJobs();
$scheduler->createSingleCronJob('*/2 * * * * php /home/pi/HessPiTestCron.php');


//$scheduler->createSingleCronJob('*/2 * * * * /usr/local/bin/php /home/scripts/HessPiSendBatteryStatus.php');
//$scheduler->createSingleCronJob('*/1 * * * * /usr/local/bin/php /home/scripts/HessPiSendPowerUsage.php');
//$scheduler->createSingleCronJob('*/1 * * * * sleep 30; /usr/local/bin/php /home/scripts/HessPiSendPowerUsage.php'););



?>