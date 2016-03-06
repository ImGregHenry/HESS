<?php
$result = json_decode(file_get_contents("php://input"));
echo '<pre>'.print_r($result,1).'</pre>';

$mysql_host = "mysql1.000webhost.com";
$mysql_database = "a4060350_HESS";
$mysql_user = "a4060350_HESSADM";
$mysql_password = "HessCloud1";


/*$named_array = array(
    "BatteryStatus" => array(
        array(
            'PeakScheduleID' => 1, 
			'IsEnabled' => 2, 
			'PowerLevelValue' => 3.8, 
			'PowerLevelPercent' => 4.2
        ),
        array(
            'PeakScheduleID' => 1, 
			'IsEnabled' => 1, 
			'PowerLevelValue' => 3.5, 
			'PowerLevelPercent' => 4.5
        )
    )
);
$encoded = json_encode($named_array);
$decoded = json_decode($encoded);
echo "ENCODED:\n";
echo "DECODED:\n";

*/




foreach($result->BatteryStatus as $item) {
		
	$peakScheduleID = $item->PeakScheduleID;
	$isEnabled = $item->IsEnabled;
	$recordTime =  strtotime($item->RecordTime);
	//$recordTimeMS = $item->RecordTimeMS;
	$powerLevelValue = $item->PowerLevelValue;
	$powerLevelPercent = $item->PowerLevelPercent;
    $deviceID = $item->DeviceID;
	

	date_default_timezone_set('US/Eastern');
	$DATE_FORMAT = "Y-m-d H:i:s";
	$cloudRecordTime = DATE($DATE_FORMAT, TIME());
	//$cloudRecordTimeMS = substr((string)microtime(), 2, 3);
	
	//echo "RECORD: " . $cloudRecordTime . " " . $cloudRecordTimeMS . "\r\n";

	try {
		
		$query = "INSERT INTO BatteryStatus (PeakScheduleID, DeviceID, IsEnabled, RecordTime, CloudRecordTime, PowerLevelValue, PowerLevelPercent) " //RecordTimeMS, CloudRecordTimeMS, 
			. "VALUES (:peakScheduleID, :deviceID, :isEnabled, :recordTime, :cloudRecordTime, :powerLevelValue, :powerLevelPercent)"; //:recordTimeMS, :cloudRecordTimeMS, 
		
		$conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_STR, 20);
		$stmt->bindParam(':deviceID', $deviceID, PDO::PARAM_INT);
		$stmt->bindParam(':isEnabled', $isEnabled, PDO::PARAM_INT);
		$stmt->bindParam(':recordTime', date("Y-m-d H:i:s", $recordTime), PDO::PARAM_STR);
		//$stmt->bindParam(':recordTimeMS', $recordTimeMS, PDO::PARAM_INT);
		$stmt->bindParam(':cloudRecordTime', $cloudRecordTime, PDO::PARAM_STR);
		//$stmt->bindParam(':cloudRecordTimeMS', $cloudRecordTimeMS, PDO::PARAM_INT);
		$stmt->bindParam(':powerLevelValue', floatval($powerLevelValue), PDO::PARAM_STR);
		$stmt->bindParam(':powerLevelPercent', floatval($powerLevelPercent), PDO::PARAM_STR);
	
		$stmt->execute();
		
	}
	catch(PDOException $e)
	{
		echo "ERROR: $e";
        return;
	}	
}

?>
