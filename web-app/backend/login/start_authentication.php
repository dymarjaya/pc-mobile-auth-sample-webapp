<?php

/**
 * Script to start web login authentication process with PC
 * 
 * This script
 *   - recieves Alias
 *   - creates a PC transaction (see https://repo.payconfirm.org/server/doc/v5/rest-api/#create-transaction)
 *   - remembers transaction ID for this user in temporary file to process it 
 *     after the transaction will be confirmed by a user with mobile phone
 * 
 * Notes:
 *   - Real back-end Application should use own user identifiers for a users
 *   - PC User ID should be stored with Application's User ID after Personalization process
 *   - to handle PC Transaction it's better to use database, sessions and all of this stuff
 *   - to store transaction IDs in temporary file - it's just for sample purposes
 * 
 * After this script has been called, Mobile Device with PC SDK and personalized with specified PC User ID
 * should confirm (digitally sign) created transaction
 * 
 * It will lead to callback from PC Server to pc_callback_reciever.php and changing status to
 * 'confirmed' or 'declined'
 * 
 * If status will be 'confirmed', then a user can be authorized (authentication successfull)
 * 
 * Authorization process (grant to a user rights to acceess) is handled
 * by finish_authentication.php script
 * 
 * 
 * Input JSON sample:
 *    {"alias":"86774396"}
 */

include('../config.php');

// read input JSON
$php_input = file_get_contents('php://input');
$request = (array) json_decode($php_input, true);

// check if alias is specified in the request
if (!isset($request['alias'])) {
    header("HTTP/1.0 400 Bad Request", true, 400);
    header("Content-Type: application/json");
    die(json_encode(array('error'=>'alias not specified')));
}

// Get Alias
$alias_value = $request['alias'];

// Get PC User ID
$pcuid = get_pcuserid($alias_value)[$alias_value];
$user_id = $pcuid['pc_user_id'];

// Create Date & Time
date_default_timezone_set('Asia/Jakarta');
$date = date("F j, Y, g:i a");

$text = array("alias" => $alias_value, "date" => $date);

// Params to create PC Transaction, see https://repo.payconfirm.org/server/doc/v5/rest-api/#create-transaction
$create_transaction_params = array(
    'transaction_data' => array(
        // to authenticate a user we can use some random transaction content
        // 'text' => bin2hex(random_bytes(32))
        'text' => $text
    ),
    'callback_url' => $callback_receiver_url,
    'notification_type' => 'Push'
);

// encode params to JSON
$data_string = json_encode($create_transaction_params);

// build request url
$create_transaction_url = $pc_url . "/" . $system_id . "/users/" . $user_id . "/transactions";

// make a request
if (!pc_request(
    $create_transaction_url,
    $data_string,
    'transaction_created',
    $transaction_info,
    $error_description,
    $error_code
)) {
    // if error - die
    header("HTTP/1.1 500 Internal request failed", true, 500);
    header("Content-Type: application/json");
    die(json_encode(array('error' => "Call to PC failed. Error code: " . $error_code . ", error description: " . $error_description)));
}

// get transaction id
$transaction_id = $transaction_info['transaction_id'];

// store the transaction id in tmp-file (you should use database instead if this)
store_transaction_info($user_id, $transaction_id, 'created');

// Format and return the result
$result = array();
$result['transaction_id'] = $transaction_id;

header("Content-Type: application/json");
print(json_encode($result));

?>