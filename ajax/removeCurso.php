<?php
	session_start();
	if ($_SESSION["user"]["tipo"] == "E") {
		if (isset($_GET["curso"])) {
			require("../include/conexao.php");
			$consulta = "SELECT inst_id 
				FROM tb_instituicao
				WHERE user_id = ?";
			unset($params);
			$params[] = $_SESSION["user"]["id"];
			$tipos = "i";
			$result = SqlPesquisar($conn, $consulta, $params, $tipos);
			if (count($result) == 1) {
				$idInst = $result[0]["inst_id"];
				$consulta = "DELETE FROM tb_instituicao_curso
					WHERE inst_id = ?
					AND curso_id = ?
					AND proc_id = ?";
				unset($params);
				$params[] = $idInst;
				$params[] = $_GET["curso"];
				$params[] = $_SESSION['user']['processo'];
				$tipos = "iii";
				SqlExecutar($conn, $consulta, $params, $tipos);

				$padrao_delete = $_SESSION['user']['processo'] . 'i' . $idInst . 'c' . $_GET['curso'] . '_*.pdf';
				$path_delete = dirname(__DIR__) . '/uploads/instituicao/' . $padrao_delete;
				$path_delete = str_replace('\\', '/', $path_delete);
				array_map('unlink', glob($path_delete));
				
				echo "Sucesso";
			}
		}
	}
?>