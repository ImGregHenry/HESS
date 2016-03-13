<?php
	include_once 'HessGlobals.php';

	$isEnabled = $_POST['IsEnabled'];
	$recordTime =  $_POST['RecordTime'];
	$powerLevelPercent = $_POST['PowerLevelPercent'];
	
	$cloudRecordTime = DATE(DB_DATE_FORMAT, TIME());
	//$cloudRecordTimeMS = substr((string)microtime(), 2, 3);

	echo "\n\nCLOUD:  PEAKID:" . $peakScheduleID . ", IsEnabled:" . $isEnabled . ", PowerLevelPercent: " . $powerLevelPercent . ", RecordTime:" . strtotime($recordTime) . "\n\n"; 

	try {
		
		$query = "INSERT INTO BatteryStatus (IsEnabled, RecordTime, CloudRecordTime, PowerLevelPercent) " //RecordTimeMS, CloudRecordTimeMS,
			. "VALUES (:isEnabled, :recordTime, :cloudRecordTime, :powerLevelPercent)"; //:recordTimeMS, :cloudRecordTimeMS,
		
		$conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$stmt = $conn->prepare($query);
		//$stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_STR, 20);
		//$stmt->bindParam(':deviceID', $deviceID, PDO::PARAM_INT);
		$stmt->bindParam(':isEnabled', $isEnabled, PDO::PARAM_INT);
		$stmt->bindParam(':recordTime', $recordTime, PDO::PARAM_STR);
		//$stmt->bindParam(':recordTimeMS', $recordTimeMS, PDO::PARAM_INT);
		$stmt->bindParam(':cloudRecordTime', $cloudRecordTime, PDO::PARAM_STR);
		//$stmt->bindParam(':cloudRecordTimeMS', $cloudRecordTimeMS, PDO::PARAM_INT);
		//$stmt->bindParam(':powerLevelValue', floatval($powerLevelValue), PDO::PARAM_STR);
		$stmt->bindParam(':powerLevelPercent', $powerLevelPercent, PDO::PARAM_STR);
	
		$stmt->execute();
		
	}
	catch(PDOException $e)
	{
		echo "ERROR: $e";
        return;
    }
?>
	