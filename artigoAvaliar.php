 <?php
	require_once('include/page_begin.php');
	
	$restrito = true;

	if ($_SESSION['user']['tipo'] == 'Professor') {
		$restrito = false;
	}
	if ($restrito) {
		$conn->close();
		unset($_SESSION['modal']);
		$_SESSION['modal']['titulo'] = "Acesso restrito";
		$_SESSION['modal']['corpo'] = "A página que você tentou acessar não está disponível para seu nível de usuário. Você foi redirecionado à página inicial.";
		$_SESSION['modal']['tipo'] = 'info';
		ob_end_clean();
		header('Location: index.php');
	}
?>
<?php
	$page_title = "Avaliar Artigos";
	require("include/html_begin.php");	
	include('include/modalAcao.php');
?>
	<!-- Inserir aqui o conteúdo personalizado de HTML <head> -->
	<script type="text/javascript">
		function download(path) {
			window.open(path, "_blank");
		}
		
		carregando = 0;
		function expandir(btn) {
			if (carregando == 0) {
				carregando = 1;
				
			}
		}
		function carregarArtigos(status) {
			var divArtigos = document.getElementById("artigos");
			divArtigos.innerHTML = "";
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					//alert(xhttp.responseText);
					divArtigos.innerHTML = xhttp.responseText;
				}
			};
			xhttp.open("POST", "ajax/carregarArtigos.php", true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send("&status=" + status);
		}
	</script>

	<style>
		.override {
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		}
	</style>
<?php
	$link_active = "e";
	require("include/body_begin.php");
?>
	<div class="container">
		<div class="wrapper">
			<h1> Artigos 
				<button class='btn btn-success override' title='Exibir Aprovados' onclick="carregarArtigos('1')"> 
					<span class='glyphicon glyphicon-ok'></span>
					Exibir Aprovados 
				</button>
				<button class='btn btn-warning override' title='Exibir Pendentes' onclick="carregarArtigos('0')"> 
					<span class='glyphicon glyphicon-option-horizontal'></span>
					Exibir Pendentes 
				</button>
				<button class='btn btn-danger override' title='Exibir Reprovados' onclick="carregarArtigos('-1')"> 
					<span class='glyphicon glyphicon-remove'></span>
					Exibir Reprovados 
				</button>
			</h1>
			<div>
				
			</div>
			<div id="artigos">
				<?php
					$consulta = "SELECT al.aluno_nome, ar.aluno_id, ar.art_id, ar.art_titulo, ar.art_resumo, ar.art_caminho, ar.art_status
						FROM tb_artigo ar 
						INNER JOIN tb_aluno al ON al.aluno_id = ar.aluno_id 
						ORDER BY al.aluno_nome
						LIMIT 10";
					$result = SqlPesquisar($conn, $consulta);
					if (count($result) > 0) {
						/*
						echo "<table class='table table-hover'>
								<tr><th colspan='5' style='text-align: center'> Artigos publicados </th></tr>
								<tr>
									<th> Aluno </th>
									<th> Título </th>
									<th> Resumo </th>
									<th> Visualizar </th>
									<th> Status </th>
								</tr>";
						*/
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
							/*
							echo "<tr>
									<td> {$row['aluno_nome']} </td>
									<td><strong> {$row['art_titulo']} </strong></td>
									<td style='width: 65%'> {$row['art_resumo']} </td>
									<td> $ver_doc </td>
									<td> $barra </td>
								</tr>";*/

							echo "
								<div class='div-frame'>
									<h3> <strong> Título: </strong> {$row['art_titulo']} $ver_doc </h3>
									<h4> <strong> Resumo: </strong> </h4>
									<p> {$row['art_resumo']} </p>
									<p> Ações: $barra </p>
								</div>";
						}
						/* echo "<tr class='separator'> </tr> </table>"; */
					}
				?>
			</div>
		</div>
	</div>
<?php 
	require_once('include/html_end.php');
	require_once('include/page_end.php');
?>