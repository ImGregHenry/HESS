<?php
    include 'HessGlobals.php';
	//function GetCurrentPeakScheduleType() {
		
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
			
            // Results array
            $Schedule = array("Schedule" => array());
			
			if ($stmt->rowCount() > 0) {
				
				// Loop over reach row
				while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
								
					// Create array from the current row
                    $temparray = array('PeakScheduleID' => $rows['PeakScheduleID'],
                                       'weekTypeID' => $rows['WeekTypeID'],
                                       'peakTypeID' => $rows['PeakTypeID'],
                                       'dbStartTime' => $rows['StartTime'],
                                       'dbEndTime' => $rows['EndTime']);
								
                    // Push the current row array into the results array
                    array_push($Schedule['Schedule'], $temparray);
                    
					/*date_default_timezone_set('US/Eastern');
					$DATE_FORMAT = "G:i:s";
					$currentDateTime = DATE($DATE_FORMAT, TIME());
		
		
					echo "StartTime: " . strtotime($temparray['dbStartTime']) . "\r\n";
					echo "EndTime: " . strtotime($temparray['dbEndTime']) . "\r\n";
					echo "CurrTime: " . strtotime($currentDateTime) . "\r\n";
					
					// Between current date range
					if (strtotime($currentDateTime) > strtotime($temparray['dbStartTime']) && strtotime($currentDateTime) < strtotime($temparray['dbEndTime'])) {
						if($temparray['weekTypeID'] == $WEEKTYPE_WEEKDAY) {
							echo "WeekDay : ";
							
							if($temparray['peakTypeID'] == $PEAKTYPE_ON) {
								echo "ON PEAK : "; 
							} else if($temparray['peakTypeID'] == $PEAKTYPE_OFF) {
								echo "OFF PEAK : "; 
							}  else if($temparray['peakTypeID'] == $PEAKTYPE_ON) {
								echo "MID PEAK : "; 
							}
							
							return $temparray['peakTypeID'];
						} else if ($temparray['weekTypeID'] == $WEEKTYPE_WEEKEND) {
							echo "Weekend : ";
							return $PEAKTYPE_OFF;
						}
						
						//echo "NOPE FUCK OFF! \r\n";
					}
                    else {
					}*/
					
				}
			}
		}
        catch (Exception $e) {
			echo "ERROR: \r\n";
			var_dump($e);
		}
	//}
	
	//$peakType = GetCurrentPeakScheduleType();
	
	//echo " THIS: " . $peakType;
    
    $payload = json_encode($Schedule);
    
    echo $payload;
?>
