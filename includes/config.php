<?php
	$connect_string = '127.0.0.1';
	$connect_username = 'asmigst2017';
	$connect_password = 'asmigst2017@';
	$connect_db = 'asmigst';
	$site_url = $_SERVER['HTTP_HOST'];
	$conn=@mysql_connect($connect_string, $connect_username, $connect_password) or die("Connection Failed");
	mysql_select_db($connect_db,$conn) or die("Database selection failure");
	
?>
