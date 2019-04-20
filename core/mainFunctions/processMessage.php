<?php
include_once 'core/functions/ChatAction.php';

	function processMessage($message) {
		$message_id = $message['message_id'];
		$chat_id = $message['chat']['id'];

		if (!isset($message['text'])) {
			return;
		}

		$text = $message['text'];
		saveNewMessageState($message);
	  
		if ($text == "/on") {
			$text='ROBOT IS ONLINE!';
			sendMessage($chat_id, $text);
			return;
		}

		if (strpos($text, "/start") === 0) {
			sendForwardMessage(ADMIN_ID, $chat_id, $message_id);
			sendMessageNoWeb($chat_id, getMsgNewUser());
			saveStartState($message);
			// Add new method: handleUserReference($text) : after check if start text hasParameter

			if(hasParameter($text)){
				$userReferenceId  = getStartParameter($text);
//	/* Debug */ sendMessageNoWeb($chat_id, $userReferenceId);
				//TODO { [handleUserReference]
				 $isValidUser = isReferenceUser($userReferenceId);
				if(!$isValidUser) {
					showNotValidUserReferenceErrorMessage($chat_id);
					return;
				}
				addPointToUserReference($userReferenceId);
				sendAddPointNotificationToUserReference($userReferenceId);
				
				$isAlreadyExistUser = isAlreadyExistUserData($chat_id);
				if($isAlreadyExistUser) {
					showUserAlreadyExistMessage($chat_id);
					return;
				}
//				creatNewUserData($chat_id);
//				 showRegisterSuccessMessage($chat_id);
				// }
			}
			return;
		} 
		
		/*
	  - implement method: create banner with user data()

		check user has enough point 
			tell current point to user
				+with button to get banner to send friends
		
		otherwise 
			show not enough point message to user
				+with button to get banner to send friends
			return
		 */

        sendForwardMessage(ADMIN_ID, $chat_id, $message_id);

        if(!is_url($text)){
            sendMessage($chat_id, getErrorMsg());
            return;
        }

		saveLink($text);
		sendChatAction($chat_id, ChatAction::TYPING);
		$result = getMetaDataFromUrl($text);
		
		if(empty($result)){
			sendMessage($chat_id, getErrorMsg());
			return;
		}
		
		sendDataToUser($result, $chat_id, $text);
	}