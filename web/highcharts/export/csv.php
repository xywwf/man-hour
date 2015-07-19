<?php
//To download a csv or xls file
$type = $_POST['type'];
$data = (string) $_POST['data'];
$extension = (string) $_POST['extension'];
$filename = (string) $_POST['filename'];

// prepare variables
if (!$filename or !preg_match('/^[A-Za-z0-9\-_ ]+$/', $filename)) {
	$filename = 'chart';
}

if (!$extension ) {
	$extension = 'csv';
}

if ($data) {
	header("Content-Disposition: attachment; filename=\"$filename.$extension\"");
	header("Content-Type: $type");	
	
	echo $data;
}
?>