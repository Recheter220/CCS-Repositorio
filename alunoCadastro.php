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
	
	$nome = isset($_POST["nome"]) ? $_POST["nome"] : "";
	$cpf = isset($_POST["cpf"]) ? $_POST["cpf"] : "";
	$email = isset($_POST["email"]) ? $_POST["email"] : "";
	$senha = isset($_POST["senha"]) ? $_POST["senha"] : "";
	$senha2 = isset($_POST["senha2"]) ? $_POST["senha2"] : "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if ($_POST["acao"] == "Salvar") {
			$erros = "";
			if (empty($nome)) {
				$erros .= "<br /> Nome";
			}
			if (empty($cpf)) {
				$erros .= "<br /> CPF";
			}
			if (empty($email)) {
				$erros .= "<br /> E-mail";
			}
			if (!isset($_POST["id"])) {
				if (empty($senha) || empty($senha2)) {
					$erros .= "<br /> Senha";
				}
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
		elseif ($_POST["acao"] == "Apagar") {
			$_SESSION["aluno"]["acao"] = "Apagar";
			$_SESSION["aluno"]["id"] = $_POST["id"];
		}
	}
?>
<?php 
	$page_title = "Cadastrar Aluno";
	if ($id != 0) {
		$page_title = "Editar Cadastro";
		if ($_SERVER["REQUEST_METHOD"] != "POST") {
			$consulta = "SELECT a.aluno_nome, u.user_cpf, u.user_email
				FROM tb_aluno a 
				INNER JOIN tb_usuario u on u.user_id = a.user_id
				WHERE a.aluno_id = ?";
			unset($params);
			$params[] = $id;
			$tipos = "i";
			$result = SqlPesquisar($conn, $consulta, $params, $tipos);
			if (count($result) > 0) {
				foreach ($result as $row) {
					$nome = $row["aluno_nome"];
					$cpf = $row["user_cpf"];
					$email = $row["user_email"];
				}
			}
			
		}
	}
	$link_active = "d";
	require("include/html_begin.php"); 
?>
	<script src="js/jquery.mask.min.js"></script>
	<script src="js/cadastro-1.1.js"></script>
	<script type="text/javascript">
		function validaCpf() {
			var txtbox = document.getElementById("alunoDoc");
			var strCpf = txtbox.value.replace(/[^0-9]/g, "");
			if (!TestaCPF(strCpf)) {
				txtbox.style.borderColor = "#d2322d";
				return false;
			}
			else {
				txtbox.style.borderColor = "";
				return true;
			}
		}
	</script>
<?php require("include/body_begin.php"); ?>
	<div class="container">
		<form class="form-center" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" onsubmit="return validaCpf()">
			<div class="form-container">
				<h1> <?php echo $page_title; ?> </h1>
				<input type="hidden" style="display: none" name="id" value="<?php echo $id; ?>" />
				<p class="text-center text-danger"> Campos marcados com * são obrigatórios </p>
				<p class="text-center text-danger"> <?php if ($erros != "") echo "Os seguintes campos não foram preenchidos: " . $erros; ?> </p>
				<input type="hidden" class="hide" name="id" value="<?php echo $id; ?>" />
				<div class="form-group">
					<label for="alunoNome"> Nome </label>
					<input type="text" class="form-control" name="nome" id="alunoNome" value="<?php echo $nome; ?>" placeholder="Nome completo" required="required" />
				</div>
				<div class="form-group">
					<label for="alunoDoc"> CPF </label>
					<input type="text" class="form-control mask-cpf" name="cpf" id="alunoDoc" value="<?php echo $cpf; ?>" placeholder="Será usado para acessar seu cadastro" required="required" onblur="validaCpf()"/>
				</div>
				<div class="form-group">
					<label for="alunoEmail"> Email </label>
					<input type="text" class="form-control" name="email" id="alunoEmail" value="<?php echo $email; ?>" placeholder="Será usado para recuperar sua senha" required="required" />
				</div>
				<?php if ($id == 0) { ?>
					<div class="form-group">
						<label for="alunoSenha"> Senha </label>
						<input type="password" class="form-control" name="senha" id="alunoSenha" required="required" />
					</div>
					<div class="form-group">
						<label for="alunoSenha2"> Confirmar senha </label>
						<input type="password" class="form-control" name="senha2" id="alunoSenha2" required="required" />
					</div>
					<input type="submit" class="btn btn-primary col-xs-12" name="acao" value="Salvar" />
					<div class="btn"></div>
				<?php } else { ?>
					<div class="form-group">
						<a href="alunoSenha.php" class="form-control btn btn-primary" id="alunoBtnSenha">
							<span style="text-align: left;">
								<span class="glyphicon glyphicon-lock"></span>
								ALTERAR SENHA
							</span>
						</a>
					</div>
					<input type="submit" class="btn btn-primary col-xs-5" name="acao" value="Salvar" />
					<input type="submit" class="btn btn-danger col-xs-5 col-xs-offset-2" name="acao" value="Apagar" />
					<div class="btn"></div>
				<?php } ?>
			</div>
		</form>
	</div>
<?php 
	require_once('include/html_end.php');
	require_once('include/page_end.php');
?>