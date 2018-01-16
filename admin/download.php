	<?php
		error_reporting(E_ERROR | E_PARSE);
		ob_start();
		session_start();

		include("../includes/config.php");include("sessiontime.php");
		include('./db_backup_library.php');
		$dbbackup = new db_backup;
		$dbbackup->connect("$connect_string","$connect_username","$connect_password","$connect_db");
		$dbbackup->backup();
		
		if($dbbackup->save("backup/")){
			echo "Backup Saved Successfully";
			//header("location: downloadlist.php");
		}
	?>
	