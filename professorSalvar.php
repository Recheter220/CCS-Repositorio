<?php
	require_once('include/page_begin.php');
	
	$prof = $_SESSION["prof"];
	
	if ($_SESSION["prof"]["acao"] == "Salvar") {
		try {
			if ($prof["id"] == 0) { // Cadastrar novo
				$consulta = "INSERT INTO tb_usuario (user_nome, user_documento, user_email, user_senha, user_tipo)
					VALUES (?, ?, ?, ?, 'E')";
				unset($params);
				$params[] = $prof["nome"];
				$params[] = $prof["documento"];
				$params[] = $prof["email"];
				$params[] = password_hash($prof["senha"], PASSWORD_DEFAULT);
				$tipos = "ssss";
				SqlExecutar($conn, $consulta, $params, $tipos);
				$idUser = $conn->query("select LAST_INSERT_ID() as id from tb_usuario")->fetch_assoc()["id"];
				
				$consulta = "INSERT INTO tb_professor (user_id, prof_nome)
					VALUES (?, ?)";

				unset($params);
				$params[] = $idUser;
				$params[] = $prof["nome"];
				$tipos = 'is';
				SqlExecutar($conn, $consulta, $params, $tipos);

				unset($_SESSION['modal']);
				$_SESSION['modal']['titulo'] = 'Sucesso';
				$_SESSION['modal']['corpo'] = '<p> Seu cadastro foi efetuado! Agora você pode fazer login com o CPF e senha cadastrados. </p>';
				$_SESSION['modal']['tipo'] = 'success';
			}
			else { // Atualizar existente
				$consulta = "UPDATE tb_usuario u
					INNER JOIN tb_professor p ON p.user_id = u.user_id 
					SET 
					u.user_cpf = ?, 
					u.user_email = ?, 
					p.prof_nome = ? 
					WHERE p.prof_id = ?";

				unset($params);
				$params[] = $prof["cpf"];
				$params[] = $prof["email"];
				$params[] = $prof["nome"];
				$params[] = $prof["id"];
				$tipos = "sssi";
				SqlExecutar($conn, $consulta, $params, $tipos);

				unset($_SESSION['modal']);
				$_SESSION['modal']['titulo'] = 'Sucesso';
				$_SESSION['modal']['corpo'] = '<p> Seus cadastro foi atualizado com os novos dados! </p>';
				$_SESSION['modal']['tipo'] = 'success';
			}
		}
		catch (Exception $ex) {
			unset($_SESSION['modal']);
			$_SESSION['modal']['titulo'] = 'Ocorreu um erro';
			$_SESSION['modal']['corpo'] = '<p> Não foi possível salvar os dados devido a um erro no sistema. <br /> Detalhes: '. nl2br2($ex->getMessage()) .'</p>';
			$_SESSION['modal']['tipo'] = 'error';
		}
		finally {
			$conn->close();
			ob_end_clean();
			header("Location: index.php");
			die();
		}
	}
	elseif ($_SESSION['prof']['acao'] == 'Senha') {
		try {
			$consulta = "UPDATE tb_usuario u 
				SET u.user_senha = ? 
				WHERE u.user_id = ?";
			unset($params);
			$params[] = password_hash($prof['senha'], PASSWORD_DEFAULT);
			$params[] = $_SESSION['user']['id'];
			$tipos = 'si';
			SqlExecutar($conn, $consulta, $params, $tipos);
			
			unset($_SESSION['modal']);
			$_SESSION['modal']['titulo'] = 'Sucesso';
			$_SESSION['modal']['corpo'] = '<p> Sua senha foi alterada! </p>';
			$_SESSION['modal']['tipo'] = 'success';
		}
		catch (Exception $ex) {
			unset($_SESSION['modal']);
			$_SESSION['modal']['titulo'] = 'Ocorreu um erro';
			$_SESSION['modal']['corpo'] = '<p> Não foi possível alterar sua senha devido a um erro no sistema. <br /> Detalhes: '. nl2br2($ex->getMessage()) .'</p>';
			$_SESSION['modal']['tipo'] = 'error';
		}
		finally {
			$conn->close();
			ob_end_clean();
			header("Location: professorCadastro.php");
			die();
		}
	}
?>