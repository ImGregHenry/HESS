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
var_dump($encoded);
echo "DECODED:\n";
var_dump($decoded);

*/




foreach($result->BatteryStatus as $item) {
		
	$peakScheduleID = $item->PeakScheduleID;
	$isEnabled = $item->IsEnabled;
	//$recordTime = NOW();
	$powerLevelValue = $item->PowerLevelValue;
	$powerLevelPercent = $item->PowerLevelPercent;
	
	
	try {
		
		$query = "INSERT INTO BatteryStatus (PeakScheduleID, IsEnabled, RecordTime, PowerLevelValue, PowerLevelPercent) "
			. "VALUES (:peakScheduleID, :isEnabled, NOW(), :powerLevelValue, :powerLevelPercent)";
 
		
		$conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_STR, 20);
		$stmt->bindParam(':isEnabled', $isEnabled, PDO::PARAM_INT);
//		$stmt->bindParam(':recordTime', NOW(), PDO::PARAM_INT);
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