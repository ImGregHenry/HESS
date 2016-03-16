<?php
	include_once "HessGlobals.php";
	include_once "HessPiCronJobScheduler.php";

class PiStateTracker {

	public static function runPythonScript($cmd) {
		if(!DEBUG_FLAG) {
			//TODO: supress output by redirecting to /dev/null
			$command = escapeshellcmd($cmd);
			$result = exec($command);
			echo "PYTHON: " . $result;
			return $result;
		}
	}

	public static function isSystemOnline() {
		//TODO: uncomment.
		//$result = PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_CHECK_SYSTEM_STATUS);
		//return $result;
		return 0;
	}

	public static function setCronJobForOfflineMode() {
		CronJobScheduler::deleteAllCronJobs();
		CronJobScheduler::createOfflineHessCronJob();
	}

	public static function setSystemOffline() {
		$isAlreadyOffline = PiStateTracker::isSystemAlreadySetOffline();
		if($isAlreadyOffline == 1) {
			echo "SetOffline: System already offline. ($isAlreadyOffline)";
		} else {
			echo "SetOffline: Setting OFFLINE MODE!";
			PiStateTracker::insertStateTrackingEntry(0, 0, 0, 0, 0, 0, 0, 1);
			PiStatetracker::setCronJobForOfflineMode();
		}
	}

	public static function isSystemAlreadySetOffline() {
		 $query = "SELECT SetOffline FROM ScriptState"
				. " ORDER BY ScriptStateID DESC"
				. " LIMIT 1";
		
		$conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $conn->prepare($query);
		$stmt->execute();
		
		if ($stmt->rowCount() > 0) {
			$row = $stmt->fetch();
		    return $row['SetOffline'];
		}
		return 0;
	}

	public static function getCurrentPeakType() {
		try {
		    $query = "SELECT PeakScheduleID, PeakTypeID, StartTime, EndTime FROM PeakSchedule;";
			
			$conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$stmt = $conn->prepare($query);
		
			$stmt->execute();

			$row = $stmt->fetch();

			if ($stmt->execute()) {
			    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			        
					// $peakScheduleID = $row['PeakScheduleID'];
					$startTime = $row['StartTime'];
					$endTime = $row['EndTime'];
					$currTime = DATE(DB_TIME_FORMAT);
					//echo "DATES: \n" . var_dump($startTime) . " . \n" . var_dump($endDate) . " . \n" . var_dump($currDate);
					// echo "\r\nDATES: \n" . ($startTime) . " . \n" . ($endTime) . " . \n" . ($currTime);
					$strStartTime = STRTOTIME($startTime);
					$strEndTime = STRTOTIME($endTime);
					$strCurrTime = STRTOTIME($currTime);

					if ($strStartTime < $strCurrTime && $strCurrTime < $strEndTime) {
						//echo "YOU'RE IN THE ZONE: " . $row['PeakTypeID'] . ". FROM: " . $row['StartTime'] . " TO: " . $row['EndTime'];
					   	return $row['PeakTypeID'];
					}
				}
			}
		}
		catch(PDOException $e) {
			echo $e;
		}

		return PEAKTYPE_OFF;
	}

	public static function deleteAllSchedulesFromDB() {
        $query = "DELETE FROM PeakSchedule";
        
        $conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
	}

	public static function changeStateWithPythonScripts($scriptSequence, $isInitialize) {
		
		$isInverterOn = $isInverterOff = $isChargerOn = $isChargerOff = $isACWallOn = $isACWallOff = $setOffline = 0;
		echo "Running Scripts: ";
		$count = 0;
		foreach ($scriptSequence as $script) {
			echo "Script($count): " . $script;
			if ($script == PISCRIPT_INVERTER_ON) {
			    PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_INVERTER_TOGGLE);
			    sleep(10);
			    $isInverterOn = true;
			} else if ($script == PISCRIPT_INVERTER_OFF) {
				PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . ' ' . PISCRIPT_PYTHON_PATH . PISCRIPT_INVERTER_TOGGLE);
			    sleep(1);
			    $isInverterOff = true;
			} else if ($script == PISCRIPT_BATTERYCHARGER_ON) {
				PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . ' ' . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERYCHARGER_ON);
			    sleep(1);
			    $isChargerOn = true;
			} else if($script == PISCRIPT_BATTERYCHARGER_OFF) {
				PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . ' ' . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERYCHARGER_OFF);
			    sleep(1);
			    $isChargerOff = true;
			} else if($script == PISCRIPT_ACFROMWALL_ON) {
				PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . ' ' . PISCRIPT_PYTHON_PATH . PISCRIPT_ACFROMWALL_ON);
			    sleep(1);
			    $isACWallOn = true;
			}
			else if ($script == PISCRIPT_ACFROMWALL_OFF) {
				PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . ' ' . PISCRIPT_PYTHON_PATH . PISCRIPT_ACFROMWALL_OFF);
			    sleep(1);
			    $isACWallOff = true;
			} else {
				echo "ERROR: Invalid script selected.\n\n";
			}
		}

		PiStateTracker::insertStateTrackingEntry($isInitialize, $isInverterOn, $isInverterOff, 
			$isChargerOn, $isChargerOff, $isACWallOn, $isACWallOff, $setOffline);
	}


	public static function insertStateTrackingEntry($isInitialize, $isInverterOn, $isInverterOff, 
			$isChargerOn, $isChargerOff, $isACWallOn, $isACWallOff, $setOffline) {
		
		$date = DATE(DB_DATE_FORMAT, TIME());

		try {
		    $query = "INSERT INTO ScriptState (Initialize, RecordTime, InverterOn, InverterOff, ChargerOn, ChargerOff, ACWallOn, ACWallOff, SetOffline) "
					. " VALUES (:initialize, :recordTime, :inverterOn, :inverterOff, :chargerOn, :chargerOff, :acWallOn, :acWallOff, :setOffline);";
			
			$conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$stmt = $conn->prepare($query);
			$stmt->bindParam(':recordTime', $date, PDO::PARAM_STR, 20);
			$stmt->bindParam(':initialize', $isInitialize, PDO::PARAM_INT);
			$stmt->bindParam(':inverterOn', $isInverterOn, PDO::PARAM_INT);
			$stmt->bindParam(':inverterOff', $isInverterOff, PDO::PARAM_INT);
			$stmt->bindParam(':chargerOn', $isChargerOn, PDO::PARAM_INT);
			$stmt->bindParam(':chargerOff', $isChargerOff, PDO::PARAM_INT);
			$stmt->bindParam(':acWallOn', $isACWallOn, PDO::PARAM_INT);
			$stmt->bindParam(':acWallOff', $isACWallOff, PDO::PARAM_INT);
			$stmt->bindParam(':setOffline', $setOffline, PDO::PARAM_INT);
		
			$stmt->execute();
		}
		catch(PDOException $e) {
			echo $e;
		}
	}


	public static function isInverterStateOn() {
		
		$date = DATE(DB_DATE_FORMAT, TIME());

		try {
		    $query = "SELECT ScriptStateID, InverterOn, InverterOff FROM ScriptState"
				. " ORDER BY ScriptStateID DESC"
				. " LIMIT 1";
			
			$conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$stmt = $conn->prepare($query);
		
			$stmt->execute();

			if ($stmt->rowCount() > 0) {
				$row = $stmt->fetch();
				
				$isInverterOn = $row['InverterOn'];
				return $isInverterOn;
			}
		}
		catch(PDOException $e) {
			echo $e;
		}
		return 0;
	}

	public static function getPeakSchedule() {
		$date = DATE(DB_DATE_FORMAT, TIME());

		try {
		    $query = "SELECT PeakScheduleID, PeakTypeID, StartTime, EndTime FROM PeakSchedule";
			
			$conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$stmt = $conn->prepare($query);
		
			$stmt->execute();

			
			if ($stmt->rowCount() > 0) {
				$row = $stmt->fetch();
			
				$startDate = STRTOTIME($row['StartTime']);
				$endDate = STRTOTIME($row['EndTime']);
				$currDate = STRTOTIME(DateTime::createFromFormat(DB_DATE_FORMAT, TIME()));
				if ($startDate > $endDate && $currDate < $startDate)
				{
				   return $row['PeakType'];
				}
			}
		}
		catch(PDOException $e) {
			echo $e;
		}

		return PEAKTYPE_OFF;
	}

	//TODO: check for previous inverter status
	public static function setPiSystemState($peakType, $batteryStatusLevel, $isInit, $isInverterOn) {
		$scriptSequence = array();

		if($peakType == PEAKTYPE_ON ||
			$peakType == PEAKTYPE_MID_ENABLE) {
			
			if($batteryStatusLevel < 0.2) {
				array_push($scriptSequence, PISCRIPT_ACFROMWALL_ON);
				if($isInverterOn == 1) 
					array_push($scriptSequence, PISCRIPT_INVERTER_OFF);
				array_push($scriptSequence, PISCRIPT_BATTERYCHARGER_OFF);
			} else {
				array_push($scriptSequence, PISCRIPT_BATTERYCHARGER_OFF);
				if($isInverterOn == 0){
					array_push($scriptSequence, PISCRIPT_INVERTER_ON);
				}
				array_push($scriptSequence, PISCRIPT_ACFROMWALL_OFF);
			}
		} else if ($peakType == PEAKTYPE_OFF) {
			if($batteryStatusLevel > 0.95) {
				array_push($scriptSequence, PISCRIPT_ACFROMWALL_ON);
				if ($isInverterOn == 1)
					array_push($scriptSequence, PISCRIPT_INVERTER_OFF);
				array_push($scriptSequence, PISCRIPT_BATTERYCHARGER_OFF);
			} else {
				array_push($scriptSequence, PISCRIPT_ACFROMWALL_ON);
				if($isInverterOn == 1)
					array_push($scriptSequence, PISCRIPT_INVERTER_OFF);
				array_push($scriptSequence, PISCRIPT_BATTERYCHARGER_ON);
			}
		} else if ($peakType == PEAKTYPE_MID_DISABLE) {
			array_push($scriptSequence, PISCRIPT_BATTERYCHARGER_OFF);
			array_push($scriptSequence, PISCRIPT_ACFROMWALL_ON);
			if($isInverterOn == 1)
				array_push($scriptSequence, PISCRIPT_INVERTER_OFF);
		}

		PiStateTracker::changeStateWithPythonScripts($scriptSequence, $isInit);
	}

	public static function getCloudScheduleForPi() {


	    $url = "http://hess.site88.net/HessCloudGetScheduler.php";
	    
	    $ch = curl_init();
	        
	    $timestamp = DATE(DB_DATE_FORMAT, TIME());
	    //$timestampMS = round(microtime(true) * 1000);
	        
	    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	    curl_setopt( $ch, CURLOPT_URL, $url);
	        
	    $result = curl_exec($ch);
	    curl_close($ch);
	    
	    $schedule = json_decode($result);
	    // $deviceID = PI_DEVICE_ID;

	    // Erase previous entries from database.
	    PiStateTracker::deleteAllSchedulesFromDB();

	    CronJobScheduler::deleteAllCronJobs();
	    CronJobScheduler::createDefaultHessCronJobs();

	    $isRunOnce = false;
	    foreach($schedule->Schedule as $item) { 

	        // if(!$isRunOnce) {
	        //     $isNewSchedule = IsNewPiSchedule($item->PeakScheduleID);
	        //     $isRunOnce = true;            
	        // }

	        // if($isNewSchedule || true) {
	        
	        $peakScheduleID = $item->PeakScheduleID;
	        $weekTypeID = $item->WeekTypeID;
	        $peakTypeID = $item->PeakTypeID;
	        $startTime = $item->StartTime;
	        $endTime = $item->EndTime;
	        
	        $currentDateTime = DATE(DB_DATE_FORMAT, TIME());
	        
	        try {
	            
	            $query = "INSERT INTO PeakSchedule (WeekTypeID, PeakTypeID, StartTime, EndTime) "
	            . " VALUES (:weekTypeID, :peakTypeID, :startTime, :endTime)";
	            
	            $conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_PI_DATABASE, MYSQL_PI_USER, MYSQL_PI_PASSWORD);
	            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	            
	            $stmt = $conn->prepare($query);
	            $stmt->bindParam(':weekTypeID', $weekTypeID, PDO::PARAM_INT);
	            $stmt->bindParam(':peakTypeID', $peakTypeID, PDO::PARAM_INT);
	            //$stmt->bindParam(':deviceID', $deviceID, PDO::PARAM_INT);
	            $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
	            $stmt->bindParam(':endTime', $endTime, PDO::PARAM_STR);

	            $stmt->execute();
	        }
	        catch(PDOException $e)
	        {
	            echo "ERROR: $e";
	        }
	        

	        $cronScheduler::createBatterySchedulingCronJob($item->StartTime, $item->EndTime, $item->PeakTypeID);

	        // } else {
	        //     echo "OLD SCHEDULE \n\n";
	        // }
	        
	        //TODO: set current peak type   
	    }
	}
}

?>
