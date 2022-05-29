<?php

	$FNAME = 'requests.txt';
	$FILE_SIZE_LIMIT = 10240;
	
	$inputJSON = file_get_contents('php://input');
	$input = json_decode( $inputJSON, TRUE ); //convert JSON into array

	// Process JSON POST
	if (is_array(($input)))
	{
		$d = new DateTime();
		$result = "[" .$d->format('Y-m-d\TH:i:s') ."]\n";
		
		//foreach ($input as $k => $v)
		//	$result .= '[' . $k .'] = ' .$v ."\n";
		$result .= json_encode($input, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) ."\n\n";

		$current_content = file_get_contents($FNAME, false, NULL, 0, $FILE_SIZE_LIMIT);
		file_put_contents($FNAME, $result.$current_content);
		die();
	}
	
	// Process POST or GET form-data
	if ( (count($_GET) != 0) || (count($_POST) != 0) )
	{
		$d = new DateTime();
		$result = $d->format('Y-m-d\TH:i:s') ."]\n";
		
		foreach ($_GET as $k => $v)
			$result .= '$_GET[' . $k .'] = ' .$v ."\n";
		
		foreach ($_POST as $k => $v)
			$result .= '$_POST[' . $k .'] = ' .$v ."\n";
		
		$result .= "\n";
		
		$current_content = file_get_contents($FNAME, false, NULL, 0, $FILE_SIZE_LIMIT);
		file_put_contents($FNAME, $result.$current_content);
		
		die();
	}
	
	// Just print out requests data
	// $result = '';	
	// $file = file($FNAME);
	// for ($i = max(0, count($file) - 100); $i < count($file); $i++) {
	//  $result .= $file[$i];
	// }
	
	// $result = str_replace("\n[", "<br/>\n[", file_get_contents($FNAME, false, NULL, 0, $FILE_SIZE_LIMIT));
	
	print ('<pre>' .file_get_contents($FNAME, false, NULL, 0, $FILE_SIZE_LIMIT) .'</pre>');
	 
	die();	

?>
