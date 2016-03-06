<?php
//TODO: determine if this is a new schedule
//TODO: set current peak type if it is a new schedule
include 'HessPiCronJobScheduler.php'


	$cronScheduler = new CronJobScheduler();

	$WEEKTYPE_WEEKDAY = 1;
	$WEEKTYPE_WEEKEND = 2;
	
	$PEAKTYPE_OFF = 1;
	$PEAKTYPE_ON = 2;
	$PEAKTYPE_MID = 3;

	$deviceID = 19;
	$peakScheduleID = 1;
	
	try {
		
		$query = "SELECT  PeakScheduleID, WeekTypeID, PeakTypeID, StartTime, EndTime"
			. " FROM PeakSchedule"
			. " WHERE PeakScheduleID = :peakScheduleID;";
		
		$conn = new PDO("mysql:host=MYSQL_CLOUD_HOST;dbname=MYSQL_CLOUD_DATABASE", MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_INT);
		$stmt->execute();
		
        # Results array
        $Schedule = array("Schedule" => array());
		
		if ($stmt->rowCount() > 0) {
			
			# Loop over reach row
			while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
							
				// Create array from the current row
                $temparray = array('PeakScheduleID' => $rows['PeakScheduleID'],
                                   'weekTypeID' => $rows['WeekTypeID'],
                                   'peakTypeID' => $rows['PeakTypeID'],
                                   'dbStartTime' => $rows['StartTime'],
                                   'dbEndTime' => $rows['EndTime']);
							
                // Push the current row array into the results array
                array_push($Schedule['Schedule'], $temparray);


                //TODO: new schedule?
                $isNewSchedule = true;
    
                if(isNewSchedule) {
	                $cronJobTimingText = $cronScheduler::createCronJobStringFromTimes();
	                $cronScheduler::deleteAllCronJobs();

	                $cronScheduler::createDefaultHessCronJobs();
	                $cronScheduler::createBatterySchedulingCronJob($rows['StartTime'], $rows['EndTime'], $rows['PeakTypeID']);
				}
				//TODO: set current peak type	
			}
		}
	}
    catch (Exception $e) {
		echo "ERROR: \r\n";
		var_dump($e);
	}
	
	
	//echo " THIS: " . $peakType;
    
    $payload = json_encode($Schedule);
    
    echo $payload;
?>
