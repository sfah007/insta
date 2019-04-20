<?php
include_once 'core/functions/ChatAction.php';

function processMessage($message) {
    $message_id = $message['message_id'];
    $chat_id = $message['chat']['id'];

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
        $inviteLink = BOT_URL . "?start=" . $chat_id;
        $total_photos = getUserProfilePhotos(BOT_ID);
        $total_count = $total_photos['total_count'];

        if ($total_count = 0) {
             showInviteMessage($chat_id, $inviteLink);
            return;
        }

        $photos = $total_photos['photos'];
        $photo = $photos[0][count($photos[0]) - 1]['file_id'];

        $inviteMessage = getInviteMessageText($inviteLink);
        sendPhoto($chat_id,$photo,$inviteMessage);
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

    if (!is_url($text)) {
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