<?php
	require_once('include/page_begin.php');
	$erros = "";
	
	$id = 0;
	if (isset($_SESSION["user"]["tipo"]) && $_SESSION["user"]["tipo"] == "Administrador") {
		$consulta = "SELECT adm_id FROM tb_admin WHERE user_id = ?";
		unset($params);
		$params[] = $_SESSION["user"]["id"];
		$tipos = "i";
		$id = SqlPesquisar($conn, $consulta, $params, $tipos)[0]["adm_id"];
	}
	
	$nome = isset($_POST["nome"]) ? $_POST["nome"] : "";
	$cpf = isset($_POST["cpf"]) ? $_POST["cpf"] : "";
	$email = isset($_POST["email"]) ? $_POST["email"] : "";
	$logo = isset($_POST["logo"]) ? $_POST["logo"] : "img/user_default.png";
	
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
			if (empty($erros)) {
				foreach(array_keys($_POST) as $chave) {
					$_SESSION["adm"][$chave] = $_POST[$chave];
				}
				
				header("Location: adminSalvar.php");
				die();
			}
		}
		elseif ($_POST["acao"] == "Apagar") {
			$_SESSION["adm"]["acao"] = "Apagar";
			$_SESSION["adm"]["id"] = $_POST["id"];
			
			header("Location: adminSalvar.php");
			die();
		}
	}
?>
<?php
	if ($id != 0) {
		$page_title = "Editar Cadastro";
		if ($_SERVER["REQUEST_METHOD"] != "POST") {
			$consulta = "SELECT adm_nome, user_cpf, user_email
				FROM tb_admin a 
				INNER JOIN tb_usuario u ON u.user_id = a.user_id
				WHERE adm_id = ?";
			unset($params);
			$params[] = $id;
			$tipos = "i";
			$result = SqlPesquisar($conn, $consulta, $params, $tipos);
			if (count($result) > 0) {
				foreach ($result as $row) {
					$nome = $row["adm_nome"];
					$cpf = $row["user_cpf"];
					$email = $row["user_email"];
				}
			}
			
		}
	}
	$link_active = "s";
	require("include/html_begin.php"); 
?>
	<script src="js/jquery.mask.min.js"></script>
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
					<label for="admNome"> Nome: </label>
					<input type="text" class="form-control" name="nome" id="admNome" value="<?php echo $nome; ?>" required="required" />
				</div>
				<div class="form-group">
					<label for="admDoc"> CPF: </label>
					<input type="text" class="form-control cnpj" name="cpf" id="admDoc" value="<?php echo $cpf; ?>" required="required" />
				</div>
				<div class="form-group">
					<label for="admEmail"> Email: </label>
					<input type="text" class="form-control" name="email" id="admEmail" value="<?php echo $email; ?>" required="required" />
				</div>
				<div class="form-group">
					<a href="adminSenha.php" class="form-control btn btn-primary" id="admBtnSenha">
						<span style="text-align: left;">
							<span class="glyphicon glyphicon-lock"></span>
							ALTERAR SENHA
						</span>
					</a>
				</div>
				<input type="submit" class="btn btn-primary col-xs-5" name="acao" value="Salvar" />
				<input type="submit" class="btn btn-danger col-xs-5 col-xs-offset-2" name="acao" value="Apagar" />
				<div class="btn"></div>
			</div>
		</form>
		<script src="../js/custom-file-input.js"></script>
	</div>
<?php 
	require_once('include/html_end.php');
	require_once('include/page_end.php');
?>