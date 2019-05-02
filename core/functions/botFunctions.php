<?php

function logi($log) {
    file_put_contents("log/_Log" . time() . ".txt", print_r($log, true));
}

function logTo($log, $fileName) {
    file_put_contents("log/$fileName.txt", print_r($log, true));
}

function logAll($log) {
    file_put_contents('log/log_all.txt', print_r($log, true), FILE_APPEND);
    file_put_contents('log/log_all.txt', print_r("\n\n--------------------\n", true), FILE_APPEND);
}

function send($text) {
    return apiRequest("sendMessage", array('chat_id' => '107616269', "text" => $text));
}

function sendChatAction($chat_id, $action) {
    // typing, upload_photo, record_video ,upload_video, record_audio, upload_audio, upload_document, find_location , record_video_note, upload_video_note
    return apiRequest("sendChatAction", array('chat_id' => $chat_id, 'action' => $action));
}

function sendMessage($chat_id, $text) {
    return apiRequest("sendMessage", array('chat_id' => $chat_id, 'parse_mode' => 'Markdown', "text" => $text));
}

function sendMessageNoNot($chat_id, $text) {
    return apiRequest("sendMessage", array('chat_id' => $chat_id, 'parse_mode' => 'Markdown', 'disable_notification' => true, "text" => $text));
}

function sendMessageNoWeb($chat_id, $text) {
    return apiRequest("sendMessage", array('chat_id' => $chat_id, 'parse_mode' => 'Markdown', 'disable_web_page_preview' => true, "text" => $text));
}

function sendMessageKey($chat_id, $text, $keyboard) {
    return apiRequest("sendMessage", array('chat_id' => $chat_id, 'parse_mode' => 'Markdown', "text" => $text, 'reply_markup' => $keyboard));
}

function sendReplyMessage($chat_id, $message_id, $text) {
    return apiRequestWebhook("forwardMessage", array('chat_id' => $chat_id, 'parse_mode' => 'Markdown', "reply_to_message_id" => $message_id, "text" => $text));
}

function sendForwardMessage($chat_id, $from_chat_id, $message_id) {
    return apiRequest("forwardMessage", array('chat_id' => $chat_id, 'from_chat_id' => $from_chat_id, 'message_id' => $message_id));
}

function sendAudio($chat_id, $audio, $caption) {
    return apiRequest("sendAudio", array('chat_id' => $chat_id, 'audio' => $audio, 'caption' => $caption));
}

function sendPhoto($chat_id, $photo, $caption) {
    return apiRequest("sendPhoto", array('chat_id' => $chat_id, 'photo' => $photo, 'caption' => $caption));
}

function sendVideo($chat_id, $video, $caption) {
    return apiRequest("sendVideo", array('chat_id' => $chat_id, 'video' => $video, 'caption' => $caption));
}

function answerCallbackQuery($callback_query_id, $text, $show_alert = false) {
    return apiRequest("answerCallbackQuery", array('callback_query_id' => $callback_query_id, 'text' => $text, 'show_alert' => $show_alert));
}

function deleteMessage($chat_id, $message_id) {
    return apiRequest("deleteMessage", array('chat_id' => $chat_id, 'message_id' => $message_id));
}

function getUserProfilePhotos($user_id) {
    return apiRequest("getUserProfilePhotos", array('user_id' => $user_id));
}

function isBlockedUser($chat_id) {
    $path = "users/info/block.txt";
    $blockedIds = file_get_contents($path);
    return strpos($blockedIds, trim($chat_id)) !== false;
}

function blockUser($message) {
    $chat_id = $message['chat']['id'];

    if (!isset($message['reply_to_message']['forward_from'])) {
        $msg = getFailedBlockedUserNotification();
        sendMessage($chat_id, $msg);
        return;
    }
    $reply_to_message = $message['reply_to_message'];
    $forward_from = $reply_to_message['forward_from'];
    $id = $forward_from['id'];
    saveInBlockedFile($id);

    $msg = getSuccessBlockedUserNotification();
    sendMessage($chat_id, $msg);
}

function saveInBlockedFile($id) {
    file_put_contents('users/info/block.txt', $id . "\n", FILE_APPEND);
}


function saveStartInfo($message) {
    $from_id = $message['from']['id'];
    $from_username = $message['from']['username'];
    $from_first_name = $message['from']['first_name'];
    $from_last_name = $message['from']['last_name'];
    date_default_timezone_set("Europe/Vienna");
    $message_date = date("Y/n/d - G:i:s", $message['date']);
    $text = $message['text'];

    $filename = 'log/user.txt';
    $fileHandler = fopen($filename, "a");
    fwrite($fileHandler, $from_id . " ;\t " . $from_username . " ;\t " . $from_first_name . " ;\t " . $from_last_name . " ;\t " . $message_date . " ;\t " . $text . "\n");
    fclose($fileHandler);
    file_put_contents('log/start_info_bot.txt', print_r($from_id . " - " . $from_first_name . " - " . $from_username . "\n", true), FILE_APPEND);
    file_put_contents('log/start_id.txt', print_r($from_id . "\n", true), FILE_APPEND);
}

function saveNewMessageState($message) {
    $text = $message['text'];
    $from_id = $message['from']['id'];
    $from_username = $message['from']['username'];
    $from_first_name = $message['from']['first_name'];
    $from_last_name = isset($message['from']['last_name']) ? $message['from']['last_name'] : "";

    date_default_timezone_set("Asia/Tehran");
    $message_date = date("Y/n/d - G:i:s", $message['date']);
    $filename = 'log/user.txt';
    $fileHandler = fopen($filename, "a");
    fwrite($fileHandler, $from_id . " ;\t " . $from_username . " ;\t " . $from_first_name . " ;\t " . $from_last_name . " ;\t " . $message_date . " ;\t " . $text . "\n");
    fclose($fileHandler);
}

function saveStartState($message) {
    $from_username = $message['from']['username'];
    $from_first_name = $message['from']['first_name'];
    $chat_id = $message['chat']['id'];

    file_put_contents('log/start_info_bot.txt', print_r($chat_id . " - " . $from_first_name . " - " . $from_username . "\n", true), FILE_APPEND);
    file_put_contents('log/start_id.txt', print_r($chat_id . "\n", true), FILE_APPEND);
}

function saveLink($text) {
    file_put_contents('log/links.txt', print_r($text . "\n", true), FILE_APPEND);
}

function is_url($uri) {
    return preg_match('/^(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-z]{2,5}' . '((:[0-9]{1,5})?\\/.*)?$/i', $uri);
}


function containInstagram($uri) {
    logAll("cc");
    logAll(strpos($uri, "instagram"));
    return strpos($uri, "instagram") != null;
}

function getMetaDataFromUrl($text) {
    $url = $text;
    libxml_use_internal_errors(true);
    $url_contents = file_get_contents($url);

    if ($url_contents == null) {
        return "";
    }
    $doc = new DomDocument();
    $doc->loadHTML($url_contents);
    $xpath = new DOMXPath($doc);
    $query = '//*/meta[starts-with(@property, \'og:\')]';
    $metas = $xpath->query($query);
    $result = array();
    foreach ($metas as $meta) {
        $property = $meta->getAttribute('property');
        $content = $meta->getAttribute('content');
        $result[$property] = $content;
    }
    return $result;
}

function sendDataToUser($PropertyData, $chat_id, $pageUrl) {
    switch ($PropertyData['og:type']) {
        case 'instapp:photo':
            sendChatAction($chat_id, ChatAction::UPLOAD_PHOTO);
            $img = $PropertyData['og:image'];
            $caption = substr($PropertyData['og:description'], 0, 200);
            sendPhoto($chat_id, $img, $caption);
            break;
        case 'video':
            sendChatAction($chat_id, ChatAction::UPLOAD_VIDEO);
            //$tn = $PropertyData['og:image'];
            $video = $PropertyData['og:video'];
            $caption = mb_convert_encoding(substr($PropertyData['og:description'], 0, 200), "UTF-8");
            sendVideo($chat_id, $video, $caption);
            break;
        case 'profile':
            sendChatAction($chat_id, ChatAction::UPLOAD_PHOTO);
            $img = $PropertyData['og:image'];
            $hDImageUrl = getHDImage($pageUrl);
            $img = (strlen($hDImageUrl) > 10) ? $hDImageUrl : $img;
            $caption = substr($PropertyData['og:description'], 0, 200);
            $caption = str_replace('See Instagram photos and videos from', '', $caption);
            sendPhoto($chat_id, $img, $caption);
            break;
    }
}

function getHDImage($pageUrl) {
    $url_contents = file_get_contents($pageUrl);
    $regex = '/"profile_pic_url_hd"(?:.+)/';
    preg_match($regex, $url_contents, $matches);
    if ($matches == null) {
        return "";
    }
    $text = explode(",", $matches[0]);
    $urlText = explode(":\"", $text[0]);
    logAll(count($urlText));
    if (count($urlText) < 2) {
        return "";
    }
    $url = str_replace("\"", "", $urlText[1]);
    return $url;
}
