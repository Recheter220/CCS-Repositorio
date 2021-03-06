<?php
	session_start();
	if ($_SESSION["user"]["tipo"] != "Professor") {
		die("Você não tem permissão para acessar essa informação");
	}

	include("../include/conexao.php");
	include("../include/functions.php");
					
					$consulta = "SELECT al.aluno_nome, ar.aluno_id, ar.art_id, ar.art_titulo, ar.art_resumo, ar.art_caminho, ar.art_status
						FROM tb_artigo ar 
						INNER JOIN tb_aluno al ON al.aluno_id = ar.aluno_id 
						ORDER BY al.aluno_nome";
					$result = SqlPesquisar($conn, $consulta);
					if (count($result) > 0) {
						echo "<table class='table table-hover'>
								<tr><th colspan='5' style='text-align: center'> Artigos publicados </th></tr>
								<tr>
									<th> Aluno </th>
									<th> Título </th>
									<th> Resumo </th>
									<th> Visualizar </th>
									<th> Status </th>
								</tr>";
						foreach ($result as $row) {
							$id_art = $row["art_id"];
							
							if (!(empty($row["art_caminho"]))) {
								$ver_doc = "<input type='button' class='btn btn-primary center' onclick='download(\"{$row["art_caminho"]}\")' value='Visualizar' />";
								$botoes = array();
								$botoes['apv'][0] = "<button type='button' class='btn btn-primary disabled col-xs-4'> 
										<span class='glyphicon glyphicon-ok' title='Aprovado'></span>
									</button>";
								$botoes['apv'][1] = "<button class='btn btn-success col-xs-4'  
												onclick='confirm(
													\"Confirmar aprovação\", 
													\"Você tem certeza de que deseja alterar o status do artigo selecionado para <strong>aprovado</strong>?\", 
													atualizaStatusArtigo, [$id_art, 1, this]
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
													atualizaStatusArtigo, [$id_art, 0, this]
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
														atualizaStatusArtigo, [$id_art, -1, this]
													]
												)'>
											<span class='glyphicon glyphicon-remove' title='Reprovado'></span>
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
								$ver_doc = "<input type='button' class='btn btn-primary disabled center' value='Nenhum arquivo' />";
								$barra = "<span class='btn btn-success glyphicon glyphicon-ok col-xs-4 disabled' title='Aprovado'></span>
											<span class='btn btn-warning glyphicon glyphicon-option-horizontal col-xs-4 disabled' title='Pendente'></span>
											<span class='btn btn-danger glyphicon glyphicon-remove col-xs-4 disabled' title='Reprovado'></span>";
							}
							
							echo "<tr>
									<td> {$row['aluno_nome']} </td>
									<td><strong> {$row['art_titulo']} </strong></td>
									<td style='width: 65%'> {$row['art_resumo']} </td>
									<td> $ver_doc </td>
									<td> $barra </td>
								</tr>";
						}
						echo "<tr class='separator'> </tr> </table>";
					}

					
?>