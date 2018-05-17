<?php
	require_once("include/page_begin.php");
	$erros = "";
	
	$id = 0;
	if (isset($_SESSION["user"]["tipo"]) && $_SESSION["user"]["tipo"] == "A") {
		$consulta = "SELECT adm_id FROM tb_admin WHERE user_id = ?";
		unset($params);
		$params[] = $_SESSION["user"]["id"];
		$tipos = "i";
		$id = SqlPesquisar($conn, $consulta, $params, $tipos)[0]["adm_id"];
	}
	
	$nome = isset($_POST["nome"]) ? $_POST["nome"] : "";
	$cpf = isset($_POST["cpf"]) ? $_POST["cpf"] : "";
	$email = isset($_POST["email"]) ? $_POST["email"] : "";
	$senha = isset($_POST["senha"]) ? $_POST["senha"] : "";
	$senha2 = isset($_POST["senha2"]) ? $_POST["senha2"] : "";
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if ($_POST["acao"] == "Salvar") {
			$erros = "";
			if (profty($nome)) {
				$erros .= "<br /> Nome";
			}
			if (profty($cpf)) {
				$erros .= "<br /> CPF";
			}
			if (profty($email)) {
				$erros .= "<br /> E-mail";
			}
			if (!isset($_POST["id"])) {
				if (profty($senha) || profty($senha2)) {
					$erros .= "<br /> Senha";
				}
			}
			if ($senha != $senha2) {
				$erros .= "<br /> As senhas informadas são diferentes";
			}
			if (profty($erros)) {
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
	$page_title = "Cadastrar Administrador";
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
					<label for="admDoc"> cpf: </label>
					<input type="text" class="form-control cnpj" name="cpf" id="admDoc" value="<?php echo $cpf; ?>" required="required" />
				</div>
				<div class="form-group">
					<label for="admEmail"> Email: </label>
					<input type="text" class="form-control" name="email" id="admEmail" value="<?php echo $email; ?>" required="required" />
				</div>
				<div class="form-group">
					<label for="admSenha"> Senha: </label>
					<input type="password" class="form-control" name="senha" id="admSenha" required="required" />
				</div>
				<div class="form-group">
					<label for="admSenha2"> Confirmar senha: </label>
					<input type="password" class="form-control" name="senha2" id="admSenha2" required="required" />
				</div>
				<input type="submit" class="btn btn-primary col-xs-5" name="acao" value="Salvar" />
				<input type="submit" class="btn btn-danger col-xs-5 col-xs-offset-2" name="acao" value="Apagar" />
				<div class="btn"></div>
			</div>
		</form>
	</div>
<?php 
	require_once('include/html_end.php');
	require_once('include/page_end.php');
?>