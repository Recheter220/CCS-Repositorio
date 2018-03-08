<?php
	require_once('include/page_begin.php');

	if (isset($_SESSION["user"])) {
		if ($_SESSION["user"]["tipo"] == "Administrador") {
			$page_title = "Área Administrativa";
			require_once("indexAdmin.php");
		}
		elseif ($_SESSION["user"]["tipo"] == "Professor") {
			$page_title = "Área do Professor";
			require_once("indexProfessor.php");
		}
		elseif ($_SESSION["user"]["tipo"] == "Aluno") {
			$page_title = "Área do Aluno";
			require_once("indexAluno.php");
		}
	}
	else {
		ob_end_clean();
		header("Location: login.php");
		die();
	}
?>