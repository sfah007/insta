<?php
	include_once 'core/config/config.php';

	$webhook_url = str_replace("setWebHook","main","https://$_SERVER[HTTP_HOST]"."$_SERVER[REQUEST_URI]");
	$get = "https://api.telegram.org/bot".BOT_TOKEN."/setWebhook?url=".$webhook_url;
	print_r(file_get_contents($get));