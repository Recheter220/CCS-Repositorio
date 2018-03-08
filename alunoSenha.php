<?php
	require_once('include/page_begin.php');
	$erros = "";
	
	$id = 0;
	if (isset($_SESSION["user"]["tipo"]) && $_SESSION["user"]["tipo"] == "Aluno") {
		$consulta = "SELECT aluno_id FROM tb_aluno WHERE user_id = ?";
		unset($params);
		$params[] = $_SESSION["user"]["id"];
		$tipos = "i";
		$id = SqlPesquisar($conn, $consulta, $params, $tipos)[0]["aluno_id"];
	}
	if ($id == 0) {
		header('Location: alunoCadastro.php');
		$conn->close();
		die();
	}
	$senhaAtual = isset($_POST['senhaAtual']) ? $_POST['senhaAtual'] : '';
	$senha = isset($_POST["senha"]) ? $_POST["senha"] : "";
	$senha2 = isset($_POST["senha2"]) ? $_POST["senha2"] : "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if ($_POST["acao"] == "Senha") {
			if (empty($senhaAtual)) {
				$erros .= '<br /> Você deve fornecer a senha atual desta conta';
			}
			else {
				$consulta = 'SELECT user_senha FROM tb_usuario WHERE user_id = ?';
				unset($params);
				$params[] = $_SESSION['user']['id'];
				$tipos = 'i';
				$result = SqlPesquisar($conn, $consulta, $params, $tipos);
				$hash = $result[0]['user_senha'];
				if (!password_verify($senhaAtual, $hash)) {
					$erros .= '<br /> A senha informada está incorreta';
				}
			}
			if (empty($senha)) {
				$erros .= "<br /> Não foi informada uma nova senha";
			}
			if ($senha != $senha2) {
				$erros .= "<br /> As senhas informadas são diferentes";
			}
			if (empty($erros)) {
				foreach(array_keys($_POST) as $chave) {
					$_SESSION["aluno"][$chave] = $_POST[$chave];
				}
				
				header("Location: alunoSalvar.php");
				die();
			}
		}
	}
?>
<?php 
	$page_title = "Alterar Senha";
	$link_active = "d";
	require("include/html_begin.php"); 
?>
	<script src="js/jquery.mask.min.js"></script>
	<script src="js/cadastro-1.1.js"></script>
<?php require("include/body_begin.php"); ?>
	<div class="container">
		<form class="form-center" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
			<div class="form-container">
				<h1> <?php echo $page_title; ?> </h1>
				<input type="hidden" style="display: none" name="id" value="<?php echo $id; ?>" />
				<p class="text-center text-danger"> Campos marcados com * são obrigatórios </p>
				<p class="text-center text-danger"> <?php if ($erros != "") echo $erros; ?> </p>
				<input type="hidden" class="hide" name="id" value="<?php echo $id; ?>" />
				<div class="form-group">
					<label for="alunoSenha"> Senha atual </label>
					<input type="password" class="form-control" name="senhaAtual" id="alunoSenha" required="required" />
				</div>
				<div class="form-group">
					<label for="alunoSenha"> Nova senha </label>
					<input type="password" class="form-control" name="senha" id="alunoSenha" required="required" />
				</div>
				<div class="form-group">
					<label for="alunoSenha2"> Confirmar nova senha </label>
					<input type="password" class="form-control" name="senha2" id="alunoSenha2" required="required" />
				</div>
				<input type="submit" class="form-control btn btn-primary" name="acao" value="Senha" />
				<div class="btn"></div>
			</div>
		</form>
		<script src="../js/custom-file-input.js"></script>
	</div>
<?php 
	require_once('include/html_end.php');
	require_once('include/page_end.php');
?>