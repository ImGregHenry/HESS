<?php
include 'HessGlobals.php';


$peakTypeID = $_POST['PeakTypeID'];
$recordTime = $_POST['RecordTime'];
$powerUsageWatt = $_POST['PowerUsageWatt'];

try {
	
	$query = "INSERT INTO PowerUsage (PeakTypeID, RecordTime, PowerUsageWatt) "
		. "VALUES (:peakTypeID, :recordTime, :powerUsageWatt)";
	
	$conn = new PDO("mysql:host=MYSQL_CLOUD_HOST;dbname=MYSQL_CLOUD_DATABASE", MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$stmt = $conn->prepare($query);
    $stmt->bindParam(':peakTypeID', $peakTypeID, PDO:PARAM_INT);
	$stmt->bindParam(':recordTime', $recordTime, PDO::PARAM_STR);
	$stmt->bindParam(':powerUsageWatt', $powerUsageWatt, PDO::PARAM_INT);
	
	$stmt->execute();
}
catch(PDOException $e)
{
	echo "ERROR: $e";
    return;
}	

?>
