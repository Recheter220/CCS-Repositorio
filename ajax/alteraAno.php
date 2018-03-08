<?php
	session_start();
	if (isset($_POST['proc'])) {
		$_SESSION['user']['processo'] = $_POST['proc'];
	}
	die();
?>