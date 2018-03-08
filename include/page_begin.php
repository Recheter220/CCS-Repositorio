<?php 
	ob_start(); // Output Buffer 
	session_start();
	session_cache_expire(60);
	require_once("include/conexao.php");
	require_once("include/functions.php");
?>