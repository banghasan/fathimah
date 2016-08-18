<?php

if (! defined('HS')) 
  die('Tidak boleh diakses langsung.');


function exec_curl_request($handle) {
  $response = curl_exec($handle);
  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl ERROR $errno: $error\n");
    curl_close($handle);
    return false;
  }
  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);
  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }
  return $response;
}

function apiRequest($method, $parameters=null) {  
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = $GLOBALS['bot']['API_URL'].$method.'?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  $parameters["method"] = $method;
  $handle = curl_init($GLOBALS['bot']['API_URL']);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
  return exec_curl_request($handle);
}


//--- API get update
function getApiUpdates($last_id = null){
  $params = [];
  if (!empty($last_id)){
    $params = ['offset' => $last_id+1, 'limit' => 1];
  }
  //echo print_r($params, true);
  return apiRequest('getUpdates', $params);
}


// --- API kirim pesan
function sendApiMessage($chatid, $text, $msg_reply_id=false, $parse_mode=false, 
    $disablepreview = false, $force_reply= false) {

  $method = 'sendMessage';
  $data = [ 'chat_id' => $chatid, 'text'  => $text ];
    
  if ($msg_reply_id) 
      $data['reply_to_message_id'] = $msg_reply_id;
  if ($parse_mode) 
      $data['parse_mode'] = $parse_mode;
  if ($disablepreview)
      $data['disable_web_page_preview'] = $disablepreview ;
  if ($force_reply) 
     $data['reply_markup'] = json_encode(array("force_reply" => true));

  // if ($GLOBALS['bot']['debug']) print_r($data);

  return apiRequest($method, $data);
}

// --- API kirim aksi
function sendApiAction($chatid, $action='typing')
{
  $method = 'sendChatAction';
    $data = array(
        'chat_id' => $chatid,
        'action'  => $action

    );
    return apiRequest($method, $data);
}

// --- API kirim sticker berdasarkan ID File yang ada
function sendApiSticker($chatid, $sticker, $msg_reply_id = false )
{
  $method = 'sendSticker';
    $data = array(
        'chat_id' => $chatid,
        'sticker'  => $sticker
    );

    if ($msg_reply_id) 
      $data['reply_to_message_id'] = $msg_reply_id;

    return apiRequest($method, $data);

}

// --- API dapatkan status user di group
function getApiChatMember($chatid, $user_id)
{
    $method = 'getChatMember';
    $data = array(
        'chat_id' => $chatid,
        'user_id'  => $user_id
    );

    return apiRequest($method, $data);

}


// --- API teruskan pesan
function forwardApiMessage($chatid, $from_chat_id, $message_id)
{
    $method = 'forwardMessage';
    $data = array(
        'chat_id' => $chatid,
        'from_chat_id'  => $from_chat_id,
        'disable_notification' => true,
        'message_id' => $message_id
    );

    return apiRequest($method, $data);

}


function sendApiKeyboard($chatid, $text, $keyboard = Array(), $inline= false)
{
    $method = 'sendMessage';
    $replyMarkup = [
        'keyboard' => $keyboard,
        'resize_keyboard'=>true
    ];

    $data = array(
        'chat_id' => $chatid,
        'text'  => $text,
        'parse_mode' => 'Markdown'
    );

    $inline  
    ? $data['reply_markup'] = json_encode(array("inline_keyboard" => $keyboard))
    : $data['reply_markup'] = json_encode( $replyMarkup ) ;

    return apiRequest($method, $data);
}


function editMessageText($chatid, $message_id, $text, $keyboard = Array(), $inline= false)
{
    $method = 'editMessageText';
    $replyMarkup = [
        'keyboard' => $keyboard,
        'resize_keyboard'=>true
    ];

    $data = array(
        'chat_id' => $chatid,
        'message_id' => $message_id,
        'text'  => $text,
        'parse_mode' => 'Markdown',   

    );

    $inline  
    ? $data['reply_markup'] = json_encode(array("inline_keyboard" => $keyboard))
    : $data['reply_markup'] = json_encode( $replyMarkup ) ;

    return apiRequest($method, $data);
}

function sendApiHideKeyboard($chatid, $text)
{
  $method = 'sendMessage';
    $data = array(
        'chat_id' => $chatid,
        'text'  => $text,
        'parse_mode' => 'Markdown',
        'reply_markup'  =>  json_encode(array("hide_keyboard" => true))

    );
   
  return apiRequest($method, $data);
}

function getApiMe()
{
  $method = 'sendMessage';
    $data = array(
        'data' => "kosong"
    );
   
  return apiRequest($method, $data);
}


