<?php
include_once 'core/functions/ChatAction.php';

function processMessage($message) {
    $message_id = $message['message_id'];
    $chat_id = $message['chat']['id'];

    if(isBlockedUser($chat_id)){
        sendMessage($chat_id, "You are Blocked and Reported by Bot.");
        return;
    }

    if (!isset($message['text'])) {
        return;
    }

    $text = $message['text'];
    if ($text == "/lnk") {
        $link = "https://telegram.me/" . BOT_USERNAME . "?start=" . ADMIN_ID;
        sendMessage($chat_id, $link);
        return;
    }
    saveNewMessageState($message);

    if ($text == "/on") {
        $text = 'ROBOT IS ONLINE!';
        sendMessage($chat_id, $text);
        return;
    }

    if (strpos($text, "/start") === 0) {
        sendForwardMessage(ADMIN_ID, $chat_id, $message_id);
        saveStartState($message);

        mainRefStartBotRegisterNewUser($message);

        sendMessageNoWeb($chat_id, getMsgNewUser());
        return;
    }

    if (strpos($text, "/invite") === 0) {
        mainRefInviteCommand($chat_id);
        return;
    }

    $hasEnoughPoint = mainRefCheckPointsToContinueTheProcess($chat_id);

    if (!$hasEnoughPoint) {
        return;
    }

    sendForwardMessage(ADMIN_ID, $chat_id, $message_id);

    if (!is_url($text) || !containInstagram($text)) {
        sendMessage($chat_id, getErrorMsg());
        return;
    }

    saveLink($text);
    sendChatAction($chat_id, ChatAction::TYPING);
    $result = getMetaDataFromUrl($text);

    if (empty($result)) {
        sendMessage($chat_id, getErrorMsg());
        return;
    }

    sendDataToUser($result, $chat_id, $text);
}