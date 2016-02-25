<?php


$isConserveDuringMidPeak = false;
$ = 1;
$ = 2;
$ = 3;
$PISCRIPT_PATH = "";

// Runs the scripts required to switch between peak states
function applyHessPeakSchedule(int peakTypeID) {
	peakTypeID = 1;
	switch(peakTypeID) {
		case PEAKTYPE_OFF:
			SetBatteryState_OFF();
			break;
		case PEAKTYPE_ON:
			SetBatteryState_ON();
			break;
		case PEAKTYPE_MID:
			if(!isConserveDuringMidPeak) {
				SetBatteryState_OFF();
			} else {
				SetBatteryState_ON();
			}
	}
}

// Run scripts required to switch BATTERY STATE to OFF
function SetBatteryState_OFF() {
		// ACFromWallOn.py
		// BatteryChargerOn.py
		// InterverOff.py
		$output = shell_exec(escapeshellcmd(PISCRIPT_PATH . PISCRIPT_ACFROMWALL_ON));
		$output = shell_exec(escapeshellcmd(PISCRIPT_PATH . PISCRIPT_BATTERYCHARGER_ON));
		$output = shell_exec(escapeshellcmd(PISCRIPT_PATH . PISCRIPT_INVERTER_OFF`));
}

// Run scripts required to switch BATTERY STATE to ON
function SetBatteryState_ON() {
		// ACFromWallOff.py
		// BatteryChargerOff.py
		// InterverOff.py
		$output = shell_exec(escapeshellcmd(PISCRIPT_PATH . PISCRIPT_ACFROMWALL_OFF));
		$output = shell_exec(escapeshellcmd(PISCRIPT_PATH . PISCRIPT_BATTERYCHARGER_OFF));
		$output = shell_exec(escapeshellcmd(PISCRIPT_PATH . PISCRIPT_INVERTER_OFF));
}

?>