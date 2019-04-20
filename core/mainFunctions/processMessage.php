<?php
include_once 'core/functions/ChatAction.php';

	function processMessage($message) {
		$message_id = $message['message_id'];
		$chat_id = $message['chat']['id'];

		if (!isset($message['text'])) {
			return;
		}

        $text = $message['text'];
        if ($text=="/lnk") {
            $link = "https://telegram.me/".CHANNEL_USERNAME."?start=".ADMIN_ID;
            sendMessage($chat_id, $link);
            return;
        }
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
/*
            $isAlreadyExistUser = isAlreadyExistUserData($chat_id);
            if($isAlreadyExistUser) {
                showUserAlreadyExistMessage($chat_id);
                return;
            }

            creatNewUserData($chat_id);
*/


            if(hasParameter($text)){
                $userReferenceId  = getStartParameter($text);

                //TODO { [handleUserReference]

                $isValidUser = isValidReferenceUser($userReferenceId);
                if(!$isValidUser) {
                    showNotValidUserReferenceErrorMessage($chat_id);
                    return;
                }

                $isExistReferenceID = isAlreadyExistReferenceIdInUserRefIdsList($userReferenceId, $chat_id);
                if($isExistReferenceID){
                    showReferenceIdAlreadyExistMessage($chat_id);
                    return;
                }
                addPointToUserReference($userReferenceId);
                addReferenceIdToUserRefIdsList($userReferenceId, $chat_id);
                sendAddPointNotificationToUserReference($userReferenceId);


            }
            //TODO: impalement>	showRegisterSuccessMessage($chat_id);
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