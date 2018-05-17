<?php
	require_once('include/page_begin.php');
	
	$aluno = $_SESSION["aluno"];
	var_dump($_SESSION);

	if ($_SESSION["aluno"]["acao"] == "Salvar") {
		try {
			if ($aluno["id"] == 0) { // Cadastrar novo
				$consulta = "INSERT INTO tb_usuario (user_nome, user_cpf, user_email, user_senha, user_tipo)
					VALUES (?, ?, ?, ?, 3)";
				unset($params);
				$params[] = $aluno["nome"];
				$params[] = $aluno["cpf"];
				$params[] = $aluno["email"];
				$params[] = password_hash($aluno["senha"], PASSWORD_DEFAULT);
				$tipos = "ssss";
				SqlExecutar($conn, $consulta, $params, $tipos);
				$idUser = $conn->query("SELECT LAST_INSERT_ID() AS id FROM tb_usuario")->fetch_assoc()["id"];
				
				$consulta = "INSERT INTO tb_aluno (user_id, aluno_nome)
					VALUES (?, ?)";

				unset($params);
				$params[] = $idUser;
				$params[] = $aluno["nome"];
				$tipos = 'is';
				SqlExecutar($conn, $consulta, $params, $tipos);

				unset($_SESSION['modal']);
				$_SESSION['modal']['titulo'] = 'Sucesso';
				$_SESSION['modal']['corpo'] = '<p> Seu cadastro foi efetuado! Agora você pode fazer login com o CPF e senha cadastrados. </p>';
				$_SESSION['modal']['tipo'] = 'success';
			}
			else { // Atualizar existente
				$consulta = "UPDATE tb_usuario u
					INNER JOIN tb_aluno a ON a.user_id = u.user_id
					SET 
					u.user_cpf = ?,
					u.user_email = ?,
					a.aluno_nome = ?
					WHERE a.aluno_id = ?";

				unset($params);
				$params[] = $aluno["cpf"];
				$params[] = $aluno["email"];
				$params[] = $aluno["nome"];
				$params[] = $aluno["id"];
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
	elseif ($_SESSION['aluno']['acao'] == 'Senha') {
		try {
			$consulta = "UPDATE tb_usuario u 
				SET u.user_senha = ? 
				WHERE u.user_id = ?";
			unset($params);
			$params[] = password_hash($aluno['senha'], PASSWORD_DEFAULT);
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
			header("Location: index.php");
			die();
		}
	}
?>