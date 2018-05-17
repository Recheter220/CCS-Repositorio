<?php
	session_start();
	if ($_SESSION["user"]["tipo"] != "Professor") {
		die("Você não tem permissão para acessar essa informação");
	}
	if (!isset($_POST['status'])) {
		die("Um ou mais parâmetros estão ausentes na requisição");
	}

	include("../include/conexao.php");
	include("../include/functions.php");
					
	$consulta = "SELECT al.aluno_nome, ar.aluno_id, ar.art_id, ar.art_titulo, ar.art_resumo, ar.art_caminho, ar.art_status
		FROM tb_artigo ar 
		INNER JOIN tb_aluno al ON al.aluno_id = ar.aluno_id 
		WHERE ar.art_status = ? 
		ORDER BY al.aluno_nome";
	unset($params);
	$params[] = $_POST['status'];
	$tipos = 'i';
	$result = SqlPesquisar($conn, $consulta, $params, $tipos);

	if (count($result) > 0) {
		foreach ($result as $row) {
			$id_art = $row["art_id"];
			
			if (!(empty($row["art_caminho"]))) {
				$ver_doc = "<span class='btn btn-primary glyphicon glyphicon-new-window' onclick='download(\"{$row['art_caminho']}\")' title='Visualizar artigo completo'></span>";
				$botoes = array();
				$botoes['apv'][0] = "<button type='button' class='btn btn-primary disabled' title='Aprovar'> 
						<span class='glyphicon glyphicon-ok'></span>
					</button>";
				$botoes['apv'][1] = "<button class='btn btn-success' title='Aprovar' 
								onclick='confirm(
									\"Confirmar aprovação\", 
									\"Você tem certeza de que deseja alterar o status do artigo selecionado para <strong>aprovado</strong>?\", 
									atualizaStatusArtigo, [$id_art, 1, this]
								)'>
							<span class='glyphicon glyphicon-ok'></span>
						</button>";
				$botoes['pnd'][0] = "<button type='button' class='btn btn-primary disabled' title='Deixar pendente'> 
						<span class='glyphicon glyphicon-option-horizontal'></span>
					</button>";
				$botoes['pnd'][1] = "<button class='btn btn-warning' title='Deixar pendente' 
								onclick='confirm(
									\"Confirmar pendência\", 
									\"Você tem certeza de que deseja alterar o status do artigo selecionado para <strong>pendente</strong>?\", 
									atualizaStatusArtigo, [$id_art, 0, this]
								)'>
							<span class='glyphicon glyphicon-option-horizontal'></span>
						</button>";
				$botoes['rpv'][0] = "<button type='button' class='btn btn-primary disabled' title='Reprovar'> 
						<span class='glyphicon glyphicon-remove'></span>
					</button>";
				$botoes['rpv'][1] = "<button class='btn btn-danger' title='Reprovar' 
								onclick='confirm(
									\"Confirmar reprovação\", 
									\"Você tem certeza de que deseja alterar o status do artigo selecionado para <strong>reprovado</strong>?\", 
									prompt, [
										\"Motivo\", 
										\"Informe o motivo pelo qual o artigo foi reprovado\", 
										\"\", 
										atualizaStatusArtigo, [$id_art, -1, this]
									]
								)'>
							<span class='glyphicon glyphicon-remove'></span>
						</button>";
			
				if ($row["art_status"] == 1) {
					$barra = $botoes['apv'][0] . $botoes['pnd'][1] . $botoes['rpv'][1];
				}
				elseif ($row["art_status"] == 0) {
					$barra = $botoes['apv'][1] . $botoes['pnd'][0] . $botoes['rpv'][1];
				}
				elseif ($row["art_status"] == -1) {
					$barra = $botoes['apv'][1] . $botoes['pnd'][1] . $botoes['rpv'][0];
				}
			}
			else {
				$ver_doc = "<span class='btn btn-primary glyphicon glyphicon-folder-open disabled center' title='Nenhum arquivo foi enviado'></span>";
				$barra = "<span class='btn btn-success glyphicon glyphicon-ok disabled' title='Aprovado'></span>
							<span class='btn btn-warning glyphicon glyphicon-option-horizontal disabled' title='Pendente'></span>
							<span class='btn btn-danger glyphicon glyphicon-remove disabled' title='Reprovado'></span>";
			}

			echo "
				<div class='div-frame'>
					<h3> <strong> Título: </strong> {$row['art_titulo']} $ver_doc </h3>
					<h4> <strong> Resumo: </strong> </h4>
					<p> {$row['art_resumo']} </p>
					<p> Ações: $barra </p>
				</div>";
		}
	}
	else {
		
	}
?>