<?php
if (isset($_SESSION["LAST_ACTIVITY"]) && (time() - $_SESSION["LAST_ACTIVITY"] > 3600)) {
    session_destroy();  
    session_unset();  
}
$_SESSION['LAST_ACTIVITY'] = time();
?>