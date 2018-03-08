<?php
	function nl2br2($texto) { 
		$texto = str_replace(array("\r\n", "\r", "\n"), "<br />", $texto); 
		return $texto; 
	}
?>
