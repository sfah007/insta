<?php


function mainRefStartBotRegisterNewUser($message) {
    $chat_id = $message['chat']['id'];
    $text = $message['text'];

    $isAlreadyExistUser = isAlreadyExistUserData($chat_id);
    if ($isAlreadyExistUser) {
        showUserAlreadyExistMessage($chat_id);
        return true;
    }

    creatNewUserData($chat_id);

    if (hasParameter($text)) {
        $encodedUserReferenceId = getStartParameter($text);

        $userReferenceId = hexdec($encodedUserReferenceId);

        $isValidUser = isValidReferenceUser($userReferenceId);
        if (!$isValidUser) {
            showNotValidUserReferenceErrorMessage($chat_id);
            return true;
        }

        $isExistReferenceID = isAlreadyExistReferenceIdInUserRefIdsList($userReferenceId, $chat_id);
        if ($isExistReferenceID || $userReferenceId == $chat_id) {
            showReferenceIdAlreadyExistMessage($chat_id);
            return true;
        }
        addPointToUserReference($userReferenceId);
        addReferenceIdToUserRefIdsList($userReferenceId, $chat_id);
        sendAddPointNotificationToUserReference($userReferenceId);
        showRegisterSuccessMessage($chat_id);
    }
}

function mainRefInviteCommand($chat_id) {
    $encodedId = dechex($chat_id);
    $inviteLink = BOT_URL . "?start=" . $encodedId;
    $total_photos = getUserProfilePhotos(BOT_ID);
    $total_count = $total_photos['total_count'];

    if ($total_count = 0) {
        showInviteMessage($chat_id, $inviteLink);
        return;
    }

    $photos = $total_photos['photos'];
    $photo = $photos[0][count($photos[0]) - 1]['file_id'];

    $inviteMessage = getInviteMessageText($inviteLink);
    sendPhoto($chat_id, $photo, $inviteMessage);
}

function mainRefCheckPointsToContinueTheProcess($chat_id) {

    $currentPoint = getUserCurrentPoint($chat_id);

    if ($currentPoint == 0) {
        sendNoEnoughPointNotificationToUser($chat_id);
        return false;
    }


    if ($currentPoint < 5) {
        sendLessPointNotificationToUser(5, $chat_id);
    }

    decreaseUserPoint($chat_id);
    return true;

}

function decreaseUserPoint($chat_id){
    $path = "users/" . $chat_id . "/point.txt";
    $currentPoint = file_get_contents($path);
    $newPoint = $currentPoint - 1;
    file_put_contents($path, $newPoint);
}

function sendLessPointNotificationToUser($point, $chat_id) {
    $message = getLessPointNotificationMessage();
    $message = str_replace("%s", $point, $message);
    sendMessage($chat_id, $message);
    //TODO: add button to invite new People:
    // using +> sendMessageKey(chat_id,text,keyboard)
}

function sendNoEnoughPointNotificationToUser($chat_id) {
    $message = getNoEnoughPointNotificationMessage();
    sendMessage($chat_id, $message);
    //TODO: add button to invite new People:
    // using +> sendMessageKey(chat_id,text,keyboard)
}

function getUserCurrentPoint($chat_id) {
    $path = "users/" . $chat_id . "/point.txt";
    return file_get_contents($path);
}

function hasParameter($text) {
    return strlen($text) > 6;
}

function getStartParameter($text) {
    return substr($text, 7);
}

function isValidReferenceUser($userReferenceId) {
    return file_exists('users/' . $userReferenceId);
}

function isAlreadyExistReferenceIdInUserRefIdsList($userReferenceId, $chat_id) {
    $path = "users/" . $chat_id . "/reference_ids.txt";
    $referenceIds = file_get_contents($path);

    return strpos($referenceIds, $userReferenceId) != null;
}

function showNotValidUserReferenceErrorMessage($chat_id) {
    $message = getNotValidUserReferenceErrorMessage();
    sendMessage($chat_id, $message);
}

function showReferenceIdAlreadyExistMessage($chat_id) {
    $message = getReferenceIdAlreadyExistMessage();
    sendMessage($chat_id, $message);
}

function addPointToUserReference($userReferenceId) {
    $path = "users/" . $userReferenceId . "/point.txt";
    $currentPoint = file_get_contents($path);
    $newPoint = $currentPoint + 5;
    file_put_contents($path, $newPoint);
}

function addReferenceIdToUserRefIdsList($userReferenceId, $chat_id) {
    $path = "users/" . $chat_id . "/reference_ids.txt";
    file_put_contents($path, $userReferenceId . "\n", FILE_APPEND);
}

function sendAddPointNotificationToUserReference($chat_id) {
    $message = getAddPointNotificationToUserReferenceMessage();
    sendMessage($chat_id, $message);
    //TODO: add button to see current points:
    // using +> sendMessageKey(chat_id,text,keyboard)
}

function isAlreadyExistUserData($chat_id) {
    return file_exists('users/' . $chat_id);
}

function showUserAlreadyExistMessage($chat_id) {
    $message = getUserAlreadyExistMessage();
    sendMessage($chat_id, $message);
    //TODO: add button to see current points:
    // using +> sendMessageKey(chat_id,text,keyboard)
}

function creatNewUserData($chat_id) {
    if (!file_exists('users/' . $chat_id)) {
        generateDirAndFiles($chat_id);
        saveRegisterDate($chat_id);
        setDefaultPoint(5, $chat_id);
        setStepTo(0, $chat_id);
        setLevelTo(0, $chat_id);
    }
}

function setLevelTo($level, $chat_id) {
    $path = 'users/' . $chat_id . "/level.txt";
    file_put_contents($path, $level);
}

function setStepTo($step, $chat_id) {
    $path = 'users/' . $chat_id . "/step.txt";
    file_put_contents($path, $step);
}

function setDefaultPoint($point, $chat_id) {
    $path = 'users/' . $chat_id . "/point.txt";
    file_put_contents($path, $point);
}

function saveRegisterDate($chat_id) {
    $path = 'users/' . $chat_id . "/register_data.txt";
    date_default_timezone_set("Europe/Vienna");
    $date = date("Y/n/d - G:i:s");
    file_put_contents($path, $date);
}

function generateDirAndFiles($chat_id) {
    $path = 'users/' . $chat_id;
    mkdir($path, 0777, true);
    file_put_contents($path . "/step.txt", "");
    file_put_contents($path . "/register_data.txt", "");
    file_put_contents($path . "/point.txt", "");
    file_put_contents($path . "/level.txt", "");
    file_put_contents($path . "/reference_ids.txt", "");
}

function showRegisterSuccessMessage($chat_id) {
    $message = getRegisterSuccessMessage();
    sendMessage($chat_id, $message);
}

function showInviteMessage($chat_id, $inviteLink) {
    $message = getInviteMessage();
    $message .= "\n\n" . $inviteLink;
    sendMessage($chat_id, $message);
}

function getInviteMessageText($inviteLink) {
    $message = getInviteMessage();
    return $message . "\n\n" . $inviteLink;
}

/* TODO: encode RefID by using HEX
 *
 * hexdec(hex_string);
 * dechex(number);
 *
 *
*/
