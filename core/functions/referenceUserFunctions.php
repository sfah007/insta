<?php
function hasParameter($text) {
    return strlen($text) > 6;
}

function getStartParameter($text) {
    return substr($text, 6);
}

function isReferenceUser($userReferenceId) {
    // TODO: implement isReferenceUser
    return false;
}

function showNotValidUserReferenceErrorMessage($chat_id) {
    $message = getNotValidUserReferenceErrorMessage();
    sendMessage($chat_id, $message);
}

function addPointToUserReference($userReferenceId) {
    // TODO: implement addPointToUserReference
}

function sendAddPointNotificationToUserReference($chat_id) {
    $message = getAddPointNotificationToUserReferenceMessage();
    sendMessage($chat_id, $message);
    //TODO: add button to see current points:
    // using +> sendMessageKey(chat_id,text,keyboard)
}

function isAlreadyExistUserData($chat_id) {
    return false;
    //TODO: implement isAlreadyExistUserData
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
    }
}

function generateDirAndFiles($chat_id) {
    $path = 'users/' . $chat_id;
    mkdir($path, 0777, true);
    file_put_contents($path . "step.txt", "");
    file_put_contents($path . "register_data.txt", "");
    file_put_contents($path . "point.txt", "");
    file_put_contents($path . "references_id.txt", "");
}