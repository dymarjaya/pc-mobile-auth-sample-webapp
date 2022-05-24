<?php

/**
 *      Configuration file for the sample
 */

// PC API URL
//    For demo purposes - request from Airome / SafeTech. For your own installation - use your own
//$pc_url = 'http://abs.net.local/pc-api';	// <--- sample value
$pc_url = 'http://192.168.41.128:8080/pc-api';	// <--- sample value to vm

// PC system-id (see docs: https://repo.payconfirm.org/server/doc/v5/rest-api/#systems-endpoint)
//    For demo purposes - request from Airome / SafeTech. For your own installation - use your own
//$system_id = '4474a630-db74-4f2e-bf69-8b66b5c5cadf';	// <--- sample value
$system_id = '0b3c8706-89d7-4378-8a96-707d5939c0f1';	// <--- sample value

// Callback Receiver URL to receive callbacks from PC Server (see docs: https://repo.payconfirm.org/server/doc/v5/rest-api/#transactions-endpoint)
//    For this sample must be address of pc_callback_reciever.php script
//$callback_receiver_url = 'http://abs.net.local/pc_samples/mobile_app_authentication/web-app/backend/pc_callback_reciever.php';	// <--- sample value
$callback_receiver_url = 'http://192.168.41.1/web-app/backend/pc_callback_reciever.php'; //<-- connect to PC Server Dymar

// Make alias and activation code persistent
$persistent_alias = true;

// Include common functions
include('common_functions.php');

// Storage-file location (database-replacement)
//$storage_file = '/tmp/pc_sample_storage.json';
$storage_file = 'C:/xampp/htdocs/web-app/tmp/pc_sample_storage.json'

?>