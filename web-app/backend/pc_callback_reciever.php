<?php

/**
 * Script to recieve callback from PC Server with information about transactions
 *
 * This script handles transaction status changing after user actions: confirm or decline
 * See https://repo.payconfirm.org/server/doc/v5/rest-api/#transactions-endpoint
 * 
 * If status will be 'confirmed', then a user can be authorized (authentication successfull)
 * 
 * Authorization process (grant to a user rights to acceess) is handled
 * by finish_authentication.php script
 * 
 */

include('config.php');


// //======== PRINT RECEIVED CALLBACK ============

// $FNAME = 'requests.txt';
// $FILE_SIZE_LIMIT = 10240;

// $inputJSON = file_get_contents('php://input');
// $input = json_decode($inputJSON, TRUE); //convert JSON into array

// // Process JSON POST
// if (is_array(($input))) {
// 	$d = new DateTime();
// 	$result = "[" . $d->format('Y-m-d\TH:i:s') . "]\n";

// 	//foreach ($input as $k => $v)
// 	//	$result .= '[' . $k .'] = ' .$v ."\n";
// 	$result .= json_encode($input, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

// 	$current_content = file_get_contents($FNAME, false, NULL, 0, $FILE_SIZE_LIMIT);
// 	file_put_contents($FNAME, $result . $current_content);
// 	die();
// }

// // Process POST or GET form-data
// if ((count($_GET) != 0) || (count($_POST) != 0)) {
// 	$d = new DateTime();
// 	$result = $d->format('Y-m-d\TH:i:s') . "]\n";

// 	foreach ($_GET as $k => $v)
// 		$result .= '$_GET[' . $k . '] = ' . $v . "\n";

// 	foreach ($_POST as $k => $v)
// 		$result .= '$_POST[' . $k . '] = ' . $v . "\n";

// 	$result .= "\n";

// 	$current_content = file_get_contents($FNAME, false, NULL, 0, $FILE_SIZE_LIMIT);
// 	file_put_contents($FNAME, $result . $current_content);

// 	die();
// }

// // Just print out requests data
// $result = '';
// $file = file($FNAME);
// for ($i = max(0, count($file) - 100); $i < count($file); $i++) {
// 	$result .= $file[$i];
// }

// //$result = str_replace("\n[", "<br/>\n[", file_get_contents($FNAME, false, NULL, 0, $FILE_SIZE_LIMIT));

// print('<pre>' . file_get_contents($FNAME, false, NULL, 0, $FILE_SIZE_LIMIT) . '</pre>');

// die();

// //===============================================


// read input JSON
$php_input = file_get_contents('php://input');
$callback_data = (array) json_decode($php_input, true);

// check if we can not parse the callback
if (!isset($callback_data['pc_callback']['type']) || !isset($callback_data['pc_callback']['version'])) {
	header("HTTP/1.0 400 Bad Request", true, 400);
	die();
}

// if there is not our callback
if (($callback_data['pc_callback']['type'] != 'transaction_callback') || ($callback_data['pc_callback']['version'] != 3)) {
	header("HTTP/1.0 400 Bad Request", true, 400);
	die();
}

$transaction_result = $callback_data['pc_callback']['result'];
$transaction_callback = $callback_data['pc_callback']['transaction_callback'];

// check if there was a error
if ($transaction_result['error_code'] != 0) {
	// do nothing
	die();
}

// get new status
$status = 'declined';  // Declined by default
if (isset($transaction_callback['confirmation'])) {
	$status = 'confirmed';   // change to declined if there was declanation
}

// store the status
store_transaction_info(null, $transaction_callback['transaction_id'], $status);
