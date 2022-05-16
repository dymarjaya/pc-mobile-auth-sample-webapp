<?php

/**
 * Script to Authorize a user (grant to a user rights to acceess)
 * 
 * This is just a sample script
 * 
 * It returns true in case of authentication successfull, false in other case
 * See description in start_authentication.php file
 * 
 * Your real authorization script should issue something like access-token
 * or set specialized cookie
 * 
 * Input JSON sample:
 *    {"alias":"86774396"}
 * 
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

// Set result, false by default
$result = array (
    'authentication_successfull' => false
);

if ('confirmed' == get_stored_transaction_info($user_id, null)) {
    $result['authentication_successfull'] = true;
}

header("Content-Type: application/json");
print(json_encode($result));

?>