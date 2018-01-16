<?
	$connect_string = 'asminew.db.6366011.hostedresource.com';
	$connect_username = 'asminew';
	$connect_password = 'Asmi@123%';
	$connect_db = 'asminew';
	$site_url = $_SERVER['HTTP_HOST'];
	mysql_connect($connect_string, $connect_username, $connect_password) or die("Connection Failed");
	mysql_select_db($connect_db) or die("Database selection failure");
	
?>
