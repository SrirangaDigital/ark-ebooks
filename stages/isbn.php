<?php
	require_once 'constants.php';
	
	$contentString = file_get_contents(JSON_PRECAST . 'book-details.json');
	$bookDetails = json_decode($contentString, true);
	
	$bookIDs = glob(UNICODE_SRC . '*', GLOB_ONLYDIR);

	foreach ($bookIDs as $bookID) {
		
		$bookID = preg_replace('/.*\/(.*)/', "$1" , $bookID);
		
	}
?>
