<?php
	session_start();
	if (isset($_GET["tipo"])) {
		require("../include/conexao.php");
		$tipo = "";
		switch($_GET["tipo"]) {
			case 0:
				$tipo = "Médio / Técnico";
				break;
			case 1:
				$tipo = "Técnico";
				break;
			case 2:
				$tipo = "Superior";
				break;
		}
		$consulta = "SELECT curso_id, curso_nome
			FROM tb_curso
			WHERE curso_tipo = ? AND proc_id = ?
			ORDER BY curso_nome";
		unset($params);
		$params[] = $tipo;
		$params[] = $_SESSION['user']['processo'];
		$tipos = "si";
		$result = SqlPesquisar($conn, $consulta, $params, $tipos);
		foreach ($result as $row) {
			echo "<option value='{$row["curso_id"]}'> {$row["curso_nome"]} </option>";
		}
		$conn->close();
		die();
	}
	else {
		die("Requisição inválida.");
	}
?>