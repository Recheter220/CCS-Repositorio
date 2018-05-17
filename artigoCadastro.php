<?php
	require_once('include/page_begin.php');

	ini_set("post_max_size", "8M");
	ini_set("upload_max_size", "2M");

	$erros = "";
	
	$id = 0;
	$restrito = true;

	if ($_SESSION['user']['tipo'] == 'Aluno') {
		if (isset($_GET['id']) || isset($_POST['id'])) {
			$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
			if ($id != 0) {
				$consulta = 'SELECT aluno_id FROM tb_artigo WHERE art_id = ?';
				unset($params);
				$params[] = $id;
				$tipos = 'i';
				$result = SqlPesquisar($conn, $consulta, $params, $tipos);
				if ($result[0]['aluno_id'] == $_SESSION['user']['aluno_id']) {
					$restrito = false;
				}
			}
			else {
				$restrito = false;
			}
		}
		else {
			$restrito = false;
		}
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
	
	$titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
	$resumo = isset($_POST['resumo']) ? $_POST['resumo'] : '';
	$success = false;
	$corpo_modal = "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if ($_POST["acao"] == "Salvar") {			
			$erros = "";
			foreach (array_keys($_FILES) as $chave) {
				$arq = $_FILES[$chave];
				$upload_path = "uploads/artigos/";
				if ($arq["error"] == UPLOAD_ERR_OK) {
					// verifica se o arquivo é um PDF
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					if(finfo_file($finfo, $arq["tmp_name"]) !== 'application/pdf') {
						$corpo_modal .=  "<p>Arquivo: <span class='vermelho'><strong><em>{$arq['name']}</em></strong></span> não está no formato PDF. Certifique-se de usar um arquivo no formato válido.</p>";

						$_SESSION['modal']['titulo'] = "Resultados do Upload";
						$_SESSION['modal']['corpo'] = $corpo_modal;
						$_SESSION['modal']['tipo'] = 'info';
					} 
					else {
						$nome = uniqid($_SESSION['user']['id'] . '_');
						$ext = strtolower(end((explode(".", $arq["name"]))));
						$nome = $nome . "." . $ext;
						$path_ok = 0;
						
						if (is_dir($upload_path)) {
							$path_ok = 1;
						}
						else {
							if (mkdir($upload_path, 0644)) {
								$path_ok = 1;
							}
							else {
								$path_ok = 0;
							}
						}
						if ($path_ok) {
							$success = move_uploaded_file($arq["tmp_name"], $upload_path . $nome);
							if (!$success) {
								$corpo_modal .= "<p>Arquivo: <span class='vermelho'><strong><em>{$arq['name']}</em></strong></span> - Não foi possível salvar no servidor. Caso o erro persista, contate o administrador.</p>";

								$_SESSION['modal']['titulo'] = "Resultados do Upload";
								$_SESSION['modal']['corpo'] = $corpo_modal;
								$_SESSION['modal']['tipo'] = 'info';
							}
							else {
								chmod($upload_path . $nome, 0644);
								$caminho = $upload_path . $nome;
								$_POST['caminho'] = $caminho;
							}
						}
					}
					finfo_close($finfo);
				}
				elseif ($arq['error'] == UPLOAD_ERR_NO_FILE)
				{
					$success = true;
					//die('ok');
				}
			}

			if (empty($titulo)) {
				$erros .= '<br /> Título';
			}
			if (empty($resumo)) {
				$erros .= '<br /> Resumo';
			}
			if (empty($erros) && $success) {
				foreach(array_keys($_POST) as $chave) {
					$_SESSION['artigo'][$chave] = $_POST[$chave];
				}
				header("Location: artigoSalvar.php");
				die();
			}
			else {
				var_dump($erros);
				var_dump($success);
			}
		}
		elseif ($_POST["acao"] == "Apagar") {
			$_SESSION["artigo"]["acao"] = "Apagar";
			$_SESSION["artigo"]["id"] = $_POST["id"];
		}
	}
?>
<?php 
	$page_title = "Cadastrar Artigo";
	if ($id != 0) {
		$page_title = "Editar Artigo";
		if ($_SERVER["REQUEST_METHOD"] != "POST") {
			$consulta = "SELECT a.art_titulo, a.art_resumo, a.art_caminho, a.art_status, a.art_userHomologacao, a.art_dataHoraHomologacao, a.art_comentarioHomologacao
				FROM tb_artigo a 
				WHERE a.art_id = ?";
			unset($params);
			$params[] = $id;
			$tipos = "i";
			$result = SqlPesquisar($conn, $consulta, $params, $tipos);
			if (count($result) > 0) {
				foreach ($result as $row) {
					$titulo = $row['art_titulo'];
					$resumo = $row['art_resumo'];
					$caminho = $row['art_caminho'];
					$status = $row['art_status'];
					$comentarioHomologacao = $row['art_comentarioHomologacao'];
				}
			}
		}
	}
	$link_active = "a";
	require("include/html_begin.php"); 
?>
	<script src="js/jquery.mask.min.js"></script>
	<script src="js/cadastro-1.1.js"></script>
	<script type="text/javascript">
		function download(path) {
			window.open(path, "_blank");
		}
	</script>
	<script src="plugins/tinymce/tinymce.min.js"></script>
	<script>tinymce.init({ selector:'textarea' });</script>
<?php require("include/body_begin.php"); ?>
	<div class="container">
		<form class="form-center" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
			<div class="form-container">
				<h1> <?php echo $page_title; ?> </h1>
				<input type="hidden" class="hide" name="id" value="<?php echo $id; ?>" />
				<p class="text-center text-danger"> Campos marcados com * são obrigatórios </p>
				<p class="text-center text-danger"> <?php if ($erros != "") echo "Os seguintes campos não foram preenchidos: " . $erros; ?> </p>
				<div class="form-group">
					<label for="artTitulo"> Título </label>
					<input type="text" class="form-control" name="titulo" id="artTitulo" value="<?php echo $titulo; ?>" placeholder="Título do artigo" required="required" />
				</div>
				<div class="form-group">
					<label for="artResumo"> Resumo </label>
					<textarea class="form-control" name="resumo" id="artResumo" rows="15" placeholder="Resumo do artigo conforme consta no Abstract"><?= $resumo ?></textarea>
				</div>
				<div class="form-group">
					<input type='file' accept='.pdf' class='inputfile inputfile-1' id='artCaminho' name='caminho' />
					<label class='btn form-control' for='artCaminho' title='Documento PDF'>
						<svg xmlns='http://www.w3.org/2000/svg' width='20' height='17' viewBox='0 0 20 17'><path d='M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z'/></svg>
						<span>Escolha um arquivo&hellip;</span>
					</label>
					<?php 
						if (!empty($caminho)) {
							if ($status == 1) {
								$botaoStatus = "<span class='btn no-hand btn-success  glyphicon glyphicon-ok form-control' title='Aprovado'></span>";
								$fileInput = "<span class='btn disabled btn-primary ' title='Documento já aprovado'> Documento já aprovado </span>";
							}
							elseif ($status == 0) {
								$botaoStatus = "<span class='btn no-hand btn-warning  glyphicon glyphicon-option-horizontal form-control' title='Pendente'></span>";
							}
							elseif ($status == -1) {
								$botaoStatus = "<button type='button' class='btn btn-danger form-control' onclick='
									swal({
										title: \"O artigo não foi aceito\",
										html: \"O artigo foi examinado por professor especializado, que não aceitou o artigo com a seguinte justificativa: <br /> <strong> {$comentarioHomologacao} </strong>\",
										type: \"error\", 
										confirmButtonText: \"OK\",
										showCancelButton: false
									})' 
									title='Clique para maiores informações' >
										<span class='glyphicon glyphicon-remove'></span>
									</button>";
							}
							else {
								$botaoStatus = "<span class='btn no-hand btn-danger glyphicon glyphicon-alert form-control' title='Erro no carregamento'></span>";
							}
						}
						else {
							$botaoStatus = "<span class='btn no-hand btn-default glyphicon glyphicon-folder-open form-control' title='Nenhum arquivo foi enviado'></span>";
							$caminho = "";
						}
						echo $botaoStatus;

						if (!(empty($caminho))) {
							echo "<input type='button' class='btn btn-primary form-control' onclick='download(\"$caminho\")' value='Visualizar último upload' />";
						}
						else {
							echo "<span class='btn btn-primary disabled form-control'> Nenhum arquivo </span>";
						}
					?>
				</div>
				<input type="submit" class="btn btn-primary form-control" style="margin-bottom: 20px;" name="acao" value="Salvar" />
			</div>
		</form>
		<script src="plugins/CustomFileInputs/js/custom-file-input.js"></script>
	</div>
<?php 
	require_once('include/html_end.php');
	require_once('include/page_end.php');
?>