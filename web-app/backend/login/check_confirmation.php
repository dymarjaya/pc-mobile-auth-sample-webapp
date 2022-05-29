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
 * 
 */

include('../config.php');

if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];
}

$result = array(
    'success' => false
);

if ('confirmed' == get_stored_transaction_info(null, $transaction_id)) {
    $result['success'] = true;
    header("Content-Type: application/json");
    print(json_encode($result));
} else {
    http_response_code(404);
    // header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
    die(json_encode($result));
}
