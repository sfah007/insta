<?php


function processCallbackQuery($query) {
    $query_id = $query['id'];
    $query_from_id = $query['from']['id'];
    $query_data = $query['data'];
    $message = $query['message'];
    $message_id = $message['message_id'];
    $chat_id = $message['chat']['id'];


    if (strpos($query_data, 'inviteLink') === 0) {
        mainRefInviteCommand($query_from_id);
    }

    if (strpos($query_data, 'currentPoints') === 0) {
        mainRefShowPointsToUser($query_from_id);
    }

    $message = "Success!";

    answerCallbackQuery($query_id, $message);

    deleteMessage($chat_id, $message_id);

}

