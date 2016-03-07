<?php
include_once 'HessGlobals.php';

$result = json_decode(file_get_contents("php://input"));
$item = $result->BatteryStatus;


		
	$peakScheduleID = $item->PeakScheduleID;
	$isEnabled = $item->IsEnabled;
	$recordTime =  strtotime($item->RecordTime);
	$powerLevelPercent = $item->PowerLevelPercent;
	
	$cloudRecordTime = DATE(DB_DATE_FORMAT, TIME());
	//$cloudRecordTimeMS = substr((string)microtime(), 2, 3);
	
	//echo "RECORD: " . $cloudRecordTime . " " . $cloudRecordTimeMS . "\r\n";

	try {
		
		$query = "INSERT INTO BatteryStatus (PeakScheduleID, IsEnabled, RecordTime, CloudRecordTime, PowerLevelPercent) " //RecordTimeMS, CloudRecordTimeMS,
			. "VALUES (:peakScheduleID, :isEnabled, :recordTime, :cloudRecordTime, :powerLevelPercent)"; //:recordTimeMS, :cloudRecordTimeMS,
		
		$conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_STR, 20);
		//$stmt->bindParam(':deviceID', $deviceID, PDO::PARAM_INT);
		$stmt->bindParam(':isEnabled', $isEnabled, PDO::PARAM_INT);
		$stmt->bindParam(':recordTime', date(DB_DATE_FORMAT, $recordTime), PDO::PARAM_STR);
		//$stmt->bindParam(':recordTimeMS', $recordTimeMS, PDO::PARAM_INT);
		$stmt->bindParam(':cloudRecordTime', $cloudRecordTime, PDO::PARAM_STR);
		//$stmt->bindParam(':cloudRecordTimeMS', $cloudRecordTimeMS, PDO::PARAM_INT);
		//$stmt->bindParam(':powerLevelValue', floatval($powerLevelValue), PDO::PARAM_STR);
		$stmt->bindParam(':powerLevelPercent', floatval($powerLevelPercent), PDO::PARAM_STR);
	
		$stmt->execute();
		
	}
	catch(PDOException $e)
	{
		echo "ERROR: $e";
        return;


?>
