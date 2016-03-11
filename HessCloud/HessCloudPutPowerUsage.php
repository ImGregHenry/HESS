<?php
include 'HessGlobals.php';


		
$recordTime = $_POST['RecordTime'];
$powerUsageInWatts = $_POST['PowerUsageInWatts'];

try {
	
	$query = "INSERT INTO PowerUsage (RecordTime, PowerUsageInWatts) "
		. "VALUES (:recordTime, :powerUsageInWatts)";
	
	$conn = new PDO("mysql:host=MYSQL_CLOUD_HOST;dbname=MYSQL_CLOUD_DATABASE", MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':recordTime', $recordTime, PDO::PARAM_STR);
	$stmt->bindParam(':powerUsageInWatts', $deviceID, PDO::PARAM_INT);
	
	$stmt->execute();
}
catch(PDOException $e)
{
	echo "ERROR: $e";
    return;
}	

?>
