<?php
	include_once "HessGlobals.php";
    include_once 'HessPiStateTracker.php';    

PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_BATTERYCHARGER_OFF);
PiStateTracker::runPythonScript(PYTHON_EXEC_PATH . " " . PISCRIPT_PYTHON_PATH . PISCRIPT_ACFROMWALL_OFF);

?>
