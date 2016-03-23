<?php
//TODO: determine if this is a new schedule
//TODO: set current peak type if it is a new schedule
include_once 'HessGlobals.php';
	
$Schedule = array("Schedule" => array());

try {
	# Only select max PeakScheduleID
	$query = "SELECT PeakScheduleID, WeekTypeID, PeakTypeID, IsUpdated, StartTime, EndTime "
	. " FROM PeakSchedule "
	. " ORDER BY StartTime ASC";

	$conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $conn->prepare($query);
	$stmt->execute();
	
    # Results array
	if ($stmt->rowCount() > 0) {
		# Loop over reach row
		while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
						
			// Create array from the current row
            $temparray = array('PeakScheduleID' => $rows['PeakScheduleID'],
                               'WeekTypeID' => $rows['WeekTypeID'],
                               'PeakTypeID' => $rows['PeakTypeID'],
                               'IsUpdated' => $rows['IsUpdated'],
                               'StartTime' => $rows['StartTime'],
                               'EndTime' => $rows['EndTime']);
						
            // Push the current row array into the results array
            array_push($Schedule['Schedule'], $temparray);
		}
	}
}
catch (Exception $e) {
	echo "ERROR: \r\n";
	var_dump($e);
}

try {   
    $isUpdated = 0;
    $query = "UPDATE PeakSchedule"
    . " SET IsUpdated = :isUpdated;";
    
    $conn = new PDO("mysql:host=" . MYSQL_CLOUD_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':isUpdated', $isUpdated, PDO::PARAM_INT);
    
    $stmt->execute();   
}
catch(PDOException $e)
{
    echo "ERROR: $e";
    return;
}


//echo " THIS: " . $peakType;

$payload = json_encode($Schedule);
echo $payload;

?>
