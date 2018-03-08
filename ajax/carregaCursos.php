<?php
	session_start();
	if (isset($_GET['inst'])) {
		$selected = isset($_GET['selected']) ? $_GET['selected'] : 0;
		require('../include/conexao.php');
		$consulta = 'SELECT c.curso_id, c.curso_nome
			FROM tb_curso c 
			INNER JOIN tb_instituicao_curso ic ON ic.curso_id = c.curso_id
			AND ic.inst_id = ?
			AND ic.proc_id = ? 
			ORDER BY curso_nome';
		unset($params);
		$params[] = $_GET['inst'];
		$params[] = 1;
		$tipos = "ii";
		$result = SqlPesquisar($conn, $consulta, $params, $tipos);
		foreach ($result as $row) {
			echo "<option value='{$row['curso_id']}'";
			if (($selected != 0)  && ($row['curso_id'] == $selected)) {
				echo ' selected="selected" ';
			}
			echo "> {$row['curso_nome']} </option>";
		}
		$conn->close();
		die();
	}
	else {
		die('Requisição inválida.');
	}
?>