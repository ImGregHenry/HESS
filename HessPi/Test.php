<?php
	include_once "HessGlobals.php";
    include_once 'HessPiStateTracker.php';    


	$isOnline = PiStateTracker::isSystemOnline();


	if($isOnline == 1) {
		echo "ONLINE!";
	} else {
		PiStateTracker::setSystemOffline();
	}

?>
