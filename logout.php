<?php
	ob_start();
	session_start();
	session_unset();
	ob_end_clean();
	header("Location: login.php");
	die();
?>