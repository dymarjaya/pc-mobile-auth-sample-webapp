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

// Get Transaction ID
if (isset($_GET['transaction_id'])){
    $transaction_id = $_GET['transaction_id'];
    }

// Set result, false by default
$result = array (
    'authentication_successfull' => false
);

if ('confirmed' == get_stored_transaction_info(null, $transaction_id)) {
    $result['authentication_successfull'] = true;
}

header("Content-Type: application/json");
print(json_encode($result));

?>