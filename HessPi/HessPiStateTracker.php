<?php
	include_once "HessGlobals.php";


	function insertStateTrackingEntry($isInitialize, $isInverterOn, $isInverterOff, $isChargerOn, $isChargerOff, $isACWallOn, $isACWallOff) {
		
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

?>
