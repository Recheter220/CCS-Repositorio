<?php
	require_once('include/page_begin.php');
	
	$erros = "";
	
	$id = 0;
	if (isset($_SESSION["user"]["tipo"]) && $_SESSION["user"]["tipo"] == "Professor") {
		$id = $_SESSION['user']['prof_id'];
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
			if (empty($documento)) {
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
					$_SESSION['prof'][$chave] = $_POST[$chave];
				}
				
				header("Location: professorSalvar.php");
				die();
			}
		}
		elseif ($_POST["acao"] == "Apagar") {
			$_SESSION['prof']["acao"] = "Apagar";
			$_SESSION['prof']["id"] = $_POST["id"];
		}
	}
?>
<?php 
	$page_title = "Cadastrar Empresa";
	if ($id != 0) {
		$page_title = "Editar Cadastro";
		if ($_SERVER["REQUEST_METHOD"] != "POST") {
			$consulta = "SELECT p.prof_nome, u.user_cpf, u.user_email
				FROM tb_professor p 
				INNER JOIN tb_usuario u on u.user_id = p.user_id
				WHERE p.prof_id = ?";
			unset($params);
			$params[] = $id;
			$tipos = "i";
			$result = SqlPesquisar($conn, $consulta, $params, $tipos);
			if (count($result) > 0) {
				foreach ($result as $row) {
					$nome = $row["prof_nome"];
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
<?php require("include/body_begin.php"); ?>
	<div class="container">
		<form class="form-center" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
			<div class="form-container">
				<h1> <?php echo $page_title; ?> </h1>
				<input type="hidden" style="display: none" name="id" value="<?php echo $id; ?>" />
				<p class="text-center text-danger"> Campos marcados com * são obrigatórios </p>
				<p class="text-center text-danger"> <?php if ($erros != "") echo "Os seguintes campos não foram preenchidos: " . $erros; ?> </p>
				<input type="hidden" class="hide" name="id" value="<?php echo $id; ?>" />
				<div class="form-group">
					<label for="profNome"> Nome: </label>
					<input type="text" class="form-control" name="nome" id="profNome" value="<?php echo $nome; ?>" required="required" />
				</div>
				<div class="form-group">
					<label for="profDoc"> CPF: </label>
					<input type="text" class="form-control mask-cpf" name="cpf" id="profDoc" value="<?php echo $cpf; ?>" required="required" />
				</div>
				<div class="form-group">
					<label for="profEmail"> Email: </label>
					<input type="text" class="form-control" name="email" id="profEmail" value="<?php echo $email; ?>" required="required" />
				</div>
				<?php if ($id == 0) { ?>
					<div class="form-group">
						<label for="profSenha"> Senha: </label>
						<input type="password" class="form-control" name="senha" id="profSenha" required="required" />
					</div>
					<div class="form-group">
						<label for="profSenha2"> Confirmar senha: </label>
						<input type="password" class="form-control" name="senha2" id="profSenha2" required="required" />

					<input type="submit" class="btn btn-primary col-xs-12" name="acao" value="Salvar" />
					<div class="btn"></div>
				</div>
				<?php } else { ?>
					<div class="form-group">
						<a href="professorSenha.php" class="form-control btn btn-primary" id="profBtnSenha">
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