<?php
	$log_Update = true;
	include_once 'core/config/config.php';
	include_once 'core/functions/generalFunctions.php';
	include_once 'core/functions/botFunctions.php';
	include_once 'core/mainFunctions/processMessage.php';
	include_once 'core/functions/messageTexts.php';
	include_once 'core/functions/referenceUserFunctions.php';

	$content = file_get_contents("php://input");
	$update = json_decode($content, true);
	if($log_Update) {
	    logTo($update,"update");
	}
	if(!$update) {
	    exit;
	}
	if(isset($update["message"])) {
	  processMessage($update["message"]);
	}