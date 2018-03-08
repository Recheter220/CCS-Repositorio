<?php
	require_once('include/page_begin.php');
	
	if (isset($_SESSION["user"])) {
		header("Location: index.php");
		die();
	}
	
	$erro = "";
	
	if (!empty($_POST["email"])) {
		$consulta = "SELECT user_id FROM tb_usuario WHERE user_email = ?";
		unset($params);
		$params[] = $_POST["email"];
		$tipos = "s";
		$cnt = count(SqlPesquisar($conn, $consulta, $params, $tipos));
		if ($cnt != 1) {
			$erro = "O e-mail informado não foi encontrado.";
			echo "<script>recuperarSenha();</script>";
		}
		else {
			$_SESSION["email"] = $_POST["email"];
			header("Location: _recuperarSenha.php");
			die();
		}
	}
	else {
		if (isset($_POST["login"])) {
			if (isset($_POST["senha"])) {
				//$login = preg_replace('/[^0-9]/', '', $_POST["login"]);
				$login = $_POST["login"];
				$senha = $_POST["senha"];
				$result = array();
				try {
					$consulta = "SELECT user_id, user_senha, user_tipo, user_cpf
						FROM tb_usuario
						WHERE user_cpf = ?";
					unset($params);
					$params[] = $login;
					$tipos = "s";
				
					$result = SqlPesquisar($conn, $consulta, $params, $tipos);
				
					$flag = 1;
					foreach ($result as $row) {
						$flag = 0;
						$dbSenha = $row["user_senha"];
						$hash = password_hash($senha, PASSWORD_DEFAULT);
						if (password_verify($senha, $dbSenha)) {
							$_SESSION["user"] = array("id" => $row["user_id"],
													  "cpf" => $row["cpf"],
													  "tipo" => $row["user_tipo"]);

							if ($row['user_tipo'] == 'Administrador') {
								$consulta = 'SELECT adm_id, adm_nome FROM tb_admin WHERE user_id = ?';
								unset($params);
								$params[] = $row['user_id'];
								$tipos = 'i';

								$result = SqlPesquisar($conn, $consulta, $params, $tipos)[0];

								$_SESSION['user']['adm_id'] = $result['adm_id'];
								$_SESSION['user']['nome'] = $result['adm_nome'];
							}
							if ($row['user_tipo'] == 'Professor') {
								$consulta = 'SELECT prof_id, prof_nome FROM tb_professor WHERE user_id = ?';
								unset($params);
								$params[] = $row['user_id'];
								$tipos = 'i';

								$result = SqlPesquisar($conn, $consulta, $params, $tipos)[0];

								$_SESSION['user']['prof_id'] = $result['prof_id'];
								$_SESSION['user']['nome'] = $result['prof_nome'];
							}
							if ($row['user_tipo'] == 'Aluno') {
								$consulta = 'SELECT aluno_id, aluno_nome FROM tb_aluno WHERE user_id = ?';
								unset($params);
								$params[] = $row['user_id'];
								$tipos = 'i';

								$result = SqlPesquisar($conn, $consulta, $params, $tipos)[0];

								$_SESSION['user']['aluno_id'] = $result['aluno_id'];
								$_SESSION['user']['nome'] = $result['aluno_nome'];
							}
							
							$conn->close();
							header("Location: index.php");
							die();
						}
						else {
							$erro = "Usuário e/ou Senha incorretos";
						}
					}
					if ($flag) {
						$erro = "Usuário e/ou Senha incorretos";
					}
				}
				catch (Exception $ex) {
					$msg .= nl2br2($ex->getMessage()) . " na linha " . $ex->getLine();
					$msg = preg_replace("/\r|\n/", "", $msg);
					$out = "<script> mostrarModal('Uma exceção foi capturada', \"Detalhes: <br />$msg\"); </script>";
				}
			}
		}
	}
?>
<?php
	$page_title = "Repositório - Login";
	require("include/html_begin.php");
?>
	<script src="js/jquery.mask.min.js"></script>
	<script src="js/cadastro-1.1.js"></script>

	<script>
		function recuperarSenha() {
			jQuery(function($){
				var current_fs, next_fs; //fieldsets
				if ($("#linkRec").html() == "Esqueceu sua senha?") {
					current_fs = $("#div-login");
					next_fs = $("#div-recupera");
					
					current_fs.fadeOut(function() {next_fs.fadeIn(); window.scrollTo(0, 0); $("#linkRec").text("Retornar ao login");});
				}
				else {
					current_fs = $("#div-recupera");
					next_fs = $("#div-login");
					
					current_fs.fadeOut(function() {next_fs.fadeIn(); window.scrollTo(0, 0); $("#linkRec").text("Esqueceu sua senha?");});
				}
			});
		}
	</script>
<?php	
	require("include/body_begin.php");
?>
		<div class="content">
			<div>
				<div class="form-center">
					<form id="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
						<div class="form-container">
							<div id="div-login">
								<h1 id="titulo">Login</h1>
								<p style="text-align: center"><span class="info-erro"><?php echo $erro; ?></span></p><br />
								<div align="center" style="padding-bottom: 20px;">
									<td> <input type="radio" name="tipoLogin" value="F" checked="checked" /> Alunos </td>
									<td> <input type="radio" name="tipoLogin" value="J" /> Instituições </td>
								</div>

								<fieldset id="login-cpf">
									<div class="input-group">
										<span class="input-group-addon">
											<i class="glyphicon glyphicon-user"></i>
										</span>
										<input type="text" class="form-control mask-cpf" name="login" placeholder="CPF"/>
									</div>
									<div class="input-group">
										<span class="input-group-addon">
											<i class="glyphicon glyphicon-lock"></i>
										</span>
										<input type="password" class="form-control" name="senha" placeholder="Senha"/>
									</div>
									<input type="submit" value="Entrar" class="btn btn-primary btn-block"/>
								</fieldset>
							</div>
							<div id="div-recupera" style="display: none;">
								<fieldset id="recuperar-senha">
									<h1>Redefinir Senha</h1>
									<p style="text-align: center"><span class="info-erro"><?php echo $erro; ?></span></p><br />
									<input type="text" class="form-control" name="email" placeholder="E-mail..."/>
									<input type="submit" value="Redefinir" class="form-control btn btn-primary btn-block"/>
								</fieldset>
							</div>
						</div>
						<div class="recSenha">
							<a href="#" id="linkRec" class="recSenha" onclick="recuperarSenha()">Esqueceu sua senha?</a>
						</div>
						<div class="recSenha">
							<a href="instituicaoCadastro.php" class="btn-primary" style="color: white;" >É uma nova instituicao? Cadastre-se!</a>
						</div>
						<div class="recSenha">
							<a href="alunoCadastro.php" class="btn-success" style="color: white;" >É um novo candidato? Cadastre-se!</a>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div style="clear: both; width: 100%; padding-top: 40px;" >
			<p style="text-align: center; font-size: 10px">&copy;&nbsp;<?php echo date("Y"); ?> - Thiago Recheter</p>
		</div>
<?php 
	require_once('include/html_end.php');
	require_once('include/page_end.php');
?>