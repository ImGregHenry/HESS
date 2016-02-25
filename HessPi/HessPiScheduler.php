<?php
	function GetCurrentPeakScheduleType() {
		$mysql_host = "localhost";
		$mysql_database = "a4060350_HESS";
		$mysql_user = "a4060350_HESSADM";
		$mysql_password = "HessCloud1";
		
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
			
			$conn = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare($query);
			$stmt->bindParam(':peakScheduleID', $peakScheduleID, PDO::PARAM_INT);
			$stmt->execute();
			
			
			if ($stmt->rowCount() > 0) {
				
				// Loop over reach row
				while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
								
					// Create array from the current row
					$peakScheduleID = $rows['PeakScheduleID'];
					$weekTypeID = $rows['WeekTypeID'];
					$peakTypeID = $rows['PeakTypeID'];
					$dbStartTime = $rows['StartTime'];
					$dbEndTime = $rows['EndTime'];
								
								
					date_default_timezone_set('US/Eastern');
					$DATE_FORMAT = "G:i:s";
					$currentDateTime = DATE($DATE_FORMAT, TIME());
		
		
					echo "StartTime: " . strtotime($dbStartTime) . "\r\n";
					echo "EndTime: " . strtotime($dbEndTime) . "\r\n";
					echo "CurrTime: " . strtotime($currentDateTime) . "\r\n";
					
					// Between current date range
					if (strtotime($currentDateTime) > strtotime($dbStartTime) && strtotime($currentDateTime) < strtotime($dbEndTime)) {
						if($weekTypeID == $WEEKTYPE_WEEKDAY) {
							echo "WeekDay : ";
							
							if($peakTypeID == $PEAKTYPE_ON) {
								echo "ON PEAK : "; 
							} else if($peakTypeID == $PEAKTYPE_OFF) {
								echo "OFF PEAK : "; 
							}  else if($peakTypeID == $PEAKTYPE_ON) {
								echo "MID PEAK : "; 
							}
							
							return $peakTypeID;
						} else if ($weekTypeID == $WEEKTYPE_WEEKEND) {
							echo "Weekend : ";
							return $PEAKTYPE_OFF;
						}
						
						echo "NOPE FUCK OFF! \r\n";
					} else {
					}
					
				}
			}
		} catch (Exception $e) {
			echo "SHIT \r\n";
			var_dump($e);
		}
	}
	
	$peakType = GetCurrentPeakScheduleType();
	
	echo " THIS: " . $peakType;
?>
