<?php
	$consulta = "SELECT aluno_id, aluno_nome FROM tb_aluno WHERE user_id = ?";
	unset($params);
	$params[] = $_SESSION["user"]["id"];
	$tipos = "i";
	$result = SqlPesquisar($conn, $consulta, $params, $tipos);
	$aluno = array();
	if (count($result) == 1) {
		$row = $result[0];
		$aluno = array("id" => $row["aluno_id"],
						 "nome" => $row["aluno_nome"]);
	}
	else {
		echo "O usuário não está relacionado a nenhum aluno.";
		$conn->close();
		die("<a href='logout.php'> Sair </a>");
	}
?>
<?php
	$link_active = 'h';
	require("include/html_begin.php");	
?>
	<!-- Inserir aqui o conteúdo personalizado de HTML <head> -->
	<script type="text/javascript">
		function download(path) {
			window.open(path, "_blank");
		}
	</script>
<?php
	require("include/body_begin.php");
?>
	<div class="div-frame">
		<h1> Bem vindo, <?php echo $aluno["nome"]; ?>! </h1>
		<div>
			<?php
				$consulta = 'SELECT art_id, art_titulo, art_resumo, art_status, art_caminho, art_comentarioHomologacao 
					FROM tb_artigo 
					WHERE aluno_id = ?';
				unset($params);
				$params[] = $_SESSION['user']['aluno_id'];
				$tipos = 'i';
				$result = SqlPesquisar($conn, $consulta, $params, $tipos);

				if (count($result) > 0) {
					echo "<h2> Seus artigos publicados no site são: </h2>";
					foreach ($result as $row) {
						$status = '';
						switch ($row['art_status']) {
							case 0:
								$status = "<span class='btn no-hand btn-warning  glyphicon glyphicon-option-horizontal' title='Pendente'></span>";
								break;
							case 1:
								$status = "<span class='btn no-hand btn-success  glyphicon glyphicon-ok' title='Aprovado'></span>";
								break;
							case -1: 
								$status = "<button type='button' class='btn btn-danger ' onclick='
									swal({
										title: \"O documento não foi aceito\",
										html: \"Será necessário fazer upload de um novo documento que atenda às especificações do edital.<br />\" + 
												\"Motivo: <strong> {$row['art_comentarioHomologacao']} </strong>\",
										type: \"error\", 
										confirmButtonText: \"OK\",
										showCancelButton: false
									})' 
									title='Clique para maiores informações' >
										<span class='glyphicon glyphicon-remove'></span>
									</button>";
								break;
							default:
								$status = "<span class='btn no-hand btn-danger glyphicon glyphicon-alert' title='Erro no carregamento'></span>";
								break;
						}
						echo "
						<div class='div-frame'>
							<h3> Título: {$row['art_titulo']} </h3>
							<h4> Resumo: </h4>
							<p> {$row['art_resumo']} </p>
							<p> $status </p>
							<input type='button' class='btn btn-primary' onclick='download(\"{$row['art_caminho']}\")' value='Visualizar artigo enviado' />
							<a class='btn btn-primary' href='artigoCadastro.php?id={$row['art_id']}'> Editar informações </a>
						</div>";
					}
				}
				else {
					echo "<h2> Você não possui artigos publicados </h2>";
				}
			?>
		</div>
	</div>
<?php 
	require_once('include/html_end.php');
	require_once('include/page_end.php');
?>