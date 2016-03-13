<?php
	include_once "HessGlobals.php";


class PiStateTracker {

	private static function runPythonScript($cmd) {
		$command = escapeshellcmd($cmd);
		return exec($command);
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
			        
					$peakScheduleID = $row['PeakScheduleID'];
					$startTime = $row['StartTime'];
					$endTime = $row['EndTime'];
					$currTime = DATE(DB_TIME_FORMAT);
					//echo "DATES: \n" . var_dump($startTime) . " . \n" . var_dump($endDate) . " . \n" . var_dump($currDate);
					echo "\r\nDATES: \n" . ($startTime) . " . \n" . ($endTime) . " . \n" . ($currTime);
					$strStartTime = STRTOTIME($startTime);
					$strEndTime = STRTOTIME($endTime);
					$strCurrTime = STRTOTIME($currTime);

					if ($strStartTime < $strCurrTime && $strCurrTime < $strEndTime) {
						echo "YOU'RE IN THE ZONE: " . $row['PeakTypeID'] . ". FROM: " . $row['StartTime'] . " TO: " . $row['EndTime'];
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
		
		$isInverterOn = $isInverterOff = $isChargerOn = $isChargerOff = $isACWallOn = $isACWallOff = 0;
		echo "Running Scripts: ";
		$count = 0;
		foreach ($scriptSequence as $script) {
			echo "Script($count): " . $script;
			if ($script == PISCRIPT_INVERTER_ON) {
			    runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERY_PERCENT);
			    sleep(30);
			    $isInverterOn = true;
			} else if ($script == PISCRIPT_INVERTER_OFF) {
				runPythonScript(PYTHON_EXEC_PATH . ' ' . PISCRIPT_PYTHON_PATH . PISCRIPT_INVERTER_TOGGLE);
			    sleep(1);
			    $isInverterOff = true;
			} else if ($script == PISCRIPT_BATTERYCHARGER_ON) {
				runPythonScript(PYTHON_EXEC_PATH . ' ' . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERYCHARGER_ON);
			    sleep(1);
			    $isChargerOn = true;
			} else if($script == PISCRIPT_BATTERYCHARGER_OFF) {
				runPythonScript(PYTHON_EXEC_PATH . ' ' . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERYCHARGER_OFF);
			    sleep(1);
			    $isChargerOff = true;
			} else if($script == PISCRIPT_ACFROMWALL_ON) {
				runPythonScript(PYTHON_EXEC_PATH . ' ' . PISCRIPT_PYTHON_PATH . PISCRIPT_ACFROMWALL_ON);
			    sleep(1);
			    $isACWallOn = true;
			}
			else if ($script == PISCRIPT_ACFROMWALL_OFF) {
				runPythonScript(PYTHON_EXEC_PATH . ' ' . PISCRIPT_PYTHON_PATH . PISCRIPT_ACFROMWALL_OFF);
			    sleep(1);
			    $isACWallOff = true;
			} else {
				echo "ERROR: Invalid script selected.\n\n";
			}
		}

		PiStateTracker::insertStateTrackingEntry($isInitialize, $isInverterOn, $isInverterOff, 
			$isChargerOn, $isChargerOff, $isACWallOn, $isACWallOff);
	}


	public static function insertStateTrackingEntry($isInitialize, $isInverterOn, $isInverterOff, 
			$isChargerOn, $isChargerOff, $isACWallOn, $isACWallOff) {
		
		$date = DATE(DB_DATE_FORMAT, TIME());

		try {
		    $query = "INSERT INTO ScriptState (Initialize, RecordTime, InverterOn, InverterOff, ChargerOn, ChargerOff, ACWallOn, ACWallOff) " //RecordTimeMS, CloudRecordTimeMS,
					. " VALUES (:initialize, :recordTime, :inverterOn, :inverterOff, :chargerOn, :chargerOff, :acWallOn, :acWallOff);";
			
			$conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
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
			
			$conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$stmt = $conn->prepare($query);
		
			$stmt->execute();

			$row = $stmt->fetch();
			$isInverterOn = $row['InverterOn'];

			return $isInverterOn;
		}
		catch(PDOException $e) {
			echo $e;
		}
	}

	public static function getPeakSchedule() {
		$date = DATE(DB_DATE_FORMAT, TIME());

		try {
		    $query = "SELECT PeakScheduleID, PeakType, StartTime, EndTime FROM PeakSchedule";
			
			$conn = new PDO("mysql:host=" . MYSQL_PI_HOST . ";dbname=" . MYSQL_CLOUD_DATABASE, MYSQL_CLOUD_USER, MYSQL_CLOUD_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$stmt = $conn->prepare($query);
		
			$stmt->execute();

			$row = $stmt->fetch();
			$peakScheduleID = $row['PeakScheduleID'];

			$startDate = STRTOTIME(DateTime::createFromFormat(DB_TIME_FORMAT, $row['StartTime']);
			$endDate = STRTOTIME(DateTime::createFromFormat(DB_TIME_FORMAT, $row['EndTime']);
			$currDate = STRTOTIME(DateTime::createFromFormat(DB_DATE_FORMAT, TIME());
			if ($startDate > $endDate && $currDate < $startDate)
			{
			   return $row['PeakType'];
			}
		}
		catch(PDOException $e) {
			echo $e;
		}
	}

	//TODO: check for previous inverter status
	public static function setPiSystemState($peakType, $batteryStatusLevel, $isInit, $isInverterOn) {
		$scriptSequence = array();

		if($peakType == PEAKTYPE_ON ||
			$peakType == PEAKTYPE_MID_ENABLE) {
			
			if($batteryStatusLevel < 0.2) {
				array_push($scriptSequence, PISCRIPT_ACFROMWALL_ON);
				if($isInverterOn && !$isInit) 
					array_push($scriptSequence, PISCRIPT_INVERTER_OFF);
				array_push($scriptSequence, PISCRIPT_BATTERYCHARGER_OFF);
			} else {
				array_push($scriptSequence, PISCRIPT_BATTERYCHARGER_OFF);
				if($isInverterOff && !$isInit)
					array_push($scriptSequence, PISCRIPT_INVERTER_ON);
				array_push($scriptSequence, PISCRIPT_ACFROMWALL_OFF);
			}
		} else if ($peakType == PEAKTYPE_OFF) {
			if($batteryStatusLevel > 0.95) {
				array_push($scriptSequence, PISCRIPT_ACFROMWALL_ON);
				if (!$isInit && $isInverterOn)
					array_push($scriptSequence, PISCRIPT_INVERTER_OFF);
				array_push($scriptSequence, PISCRIPT_BATTERYCHARGER_OFF);
			} else {
				array_push($scriptSequence, PISCRIPT_ACFROMWALL_ON);
				if(!$isInit && $isInverterOn)
					array_push($scriptSequence, PISCRIPT_INVERTER_OFF);
				array_push($scriptSequence, PISCRIPT_BATTERYCHARGER_ON);
			}
		} else if ($peakType == PEAKTYPE_MID_DISABLE) {
			array_push($scriptSequence, PISCRIPT_BATTERYCHARGER_OFF);
			array_push($scriptSequence, PISCRIPT_ACFROMWALL_ON);
			if($isInverterOn)
				array_push($scriptSequence, PISCRIPT_INVERTER_OFF);
		}

		//HessPiStateTracker::changeStateWithPythonScripts($scriptSequence, $isInit);
	}
}

?>
