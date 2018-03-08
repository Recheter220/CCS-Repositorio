<?php
	session_start();
	try {
		require("../include/functions.php");
		require("../include/conexao.php");

		$id = isset($_POST["id"]) ? $_POST["id"] : 0;
		$estado = isset($_POST["estado"]) ? $_POST["estado"] : 0;
		$motivo = isset($_POST["motivo"]) ? $_POST["motivo"] : "";

		$consulta = "UPDATE tb_artigo SET
			art_status = ?, 
			art_userHomologacao = ?, 
			art_dataHoraHomologacao = ?,
			art_comentarioHomologacao = ?
			WHERE art_id = ?";
		unset($params);
		$params[] = $estado;
		$params[] = $_SESSION["user"]["id"];
		$params[] = date("Y-m-d H:i:s");
		$params[] = $motivo;
		$params[] = $id;
		$tipos = "iissi";
		SqlExecutar($conn, $consulta, $params, $tipos); 		

		$botoes = array();
		$botoes['apv'][0] = "<button type='button' class='btn btn-primary disabled col-xs-4'> 
				<span class='glyphicon glyphicon-ok' title='Aprovado'></span>
			</button>";
		$botoes['apv'][1] = "<button class='btn btn-success col-xs-4'  
						onclick='confirm(
							\"Confirmar aprovação\", 
							\"Você tem certeza de que deseja alterar o status do artigo selecionado para <strong>aprovado</strong>?\", 
							atualizaStatusArtigo, [$id, 1, this]
						)'>
					<span class='glyphicon glyphicon-ok' title='Aprovado'></span>
				</button>";
		$botoes['pnd'][0] = "<button type='button' class='btn btn-primary disabled col-xs-4'> 
				<span class='glyphicon glyphicon-option-horizontal' title='Pendente'></span>
			</button>";
		$botoes['pnd'][1] = "<button class='btn btn-warning col-xs-4'  
						onclick='confirm(
							\"Confirmar pendência\", 
							\"Você tem certeza de que deseja alterar o status do artigo selecionado para <strong>pendente</strong>?\", 
							atualizaStatusArtigo, [$id, 0, this]
						)'>
					<span class='glyphicon glyphicon-option-horizontal' title='Pendente'></span>
				</button>";
		$botoes['rpv'][0] = "<button type='button' class='btn btn-primary disabled col-xs-4'> 
				<span class='glyphicon glyphicon-remove' title='Reprovado'></span>
			</button>";
		$botoes['rpv'][1] = "<button class='btn btn-danger col-xs-4'  
						onclick='confirm(
							\"Confirmar reprovação\", 
							\"Você tem certeza de que deseja alterar o status do artigo selecionado para <strong>reprovado</strong>?\", 
							prompt, [
								\"Motivo\", 
								\"Informe o motivo pelo qual o artigo foi reprovado\", 
								\"\", 
								atualizaStatusArtigo, [$id, -1, this]
							]
						)'>
					<span class='glyphicon glyphicon-remove' title='Reprovado'></span>
				</button>";
				
		if ($estado == 1) {
			$barra = $botoes['apv'][0] . $botoes['pnd'][1] . $botoes['rpv'][1];
		}
		elseif ($estado == 0) {
			$barra = $botoes['apv'][1] . $botoes['pnd'][0] . $botoes['rpv'][1];
		}
		elseif ($estado == -1) {
			$barra = $botoes['apv'][1] . $botoes['pnd'][1] . $botoes['rpv'][0];
		}
		echo $barra;
	}
	catch (Exception $ex) {
		die(nl2br2($ex->getMessage()));
	}
?>