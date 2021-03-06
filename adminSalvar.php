<?php
	require_once('include/page_begin.php');
	if ($_SESSION["adm"]["acao"] == "Salvar") {
		$adm = $_SESSION["adm"];
		try {
			if ($adm["id"] == 0) { // Cadastrar novo
				$consulta = "INSERT INTO tb_usuario (user_nome, user_documento, user_email, user_senha, user_tipo)
					VALUES (?, ?, ?, ?, 1)";
				unset($params);
				$params[] = $adm["nome"];
				$params[] = $adm["cpf"];
				$params[] = $adm["email"];
				$params[] = password_hash($adm["senha"], PASSWORD_DEFAULT);
				$tipos = "ssss";
				SqlExecutar($conn, $consulta, $params, $tipos);
				$idUser = $conn->query("SELECT LAST_INSERT_ID() AS id FROM tb_usuario")->fetch_assoc()["id"];
				
				$consulta = "INSERT INTO tb_admin (user_id, adm_nome)
					VALUES (?, ?)";
				unset($params);
				$params[] = $idUser;
				$params[] = $adm["nome"];
				$tipos = "is";
				SqlExecutar($conn, $consulta, $params, $tipos);

				unset($_SESSION['modal']);
				$_SESSION['modal']['titulo'] = 'Sucesso';
				$_SESSION['modal']['corpo'] = '<p> Seu cadastro foi efetuado! Agora você pode fazer login com o CPF e senha cadastrados. </p>';
				$_SESSION['modal']['tipo'] = 'success';
			}
			else { // Atualizar existente
				$consulta = "UPDATE tb_usuario u
					INNER JOIN tb_admin a ON a.user_id = u.user_id
					SET 
					user_nome = ?,
					user_documento = ?,
					user_email = ?,
					adm_nome = ?
					WHERE admin_id = ?";
				unset($params);
				$params[] = $adm["nome"];
				$params[] = $adm["documento"];
				$params[] = $adm["email"];
				$params[] = $adm["nome"];
				$params[] = $adm["id"];
				$tipos = "sssssi";
				SqlExecutar($conn, $consulta, $params, $tipos);

				unset($_SESSION['modal']);
				$_SESSION['modal']['titulo'] = 'Sucesso';
				$_SESSION['modal']['corpo'] = '<p> Seus cadastro foi atualizado com os novos dados! </p>';
				$_SESSION['modal']['tipo'] = 'success';
			}
		}
		catch (Exception $ex) {
			unset($_SESSION['modal']);
			$_SESSION['modal']['titulo'] = 'Erro';
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
	elseif ($_SESSION['adm']['acao'] == 'Senha') {
		try {
			$consulta = 'UPDATE tb_usuario 
				SET user_senha = ? 
				WHERE user_id = ?';
			unset($params);
			$params[] = password_hash($adm['senha'], PASSWORD_DEFAULT);
			$params[] = $_SESSION['user']['id'];
			SqlExecutar($conn, $consulta, $params, $tipos);

			unset($_SESSION['modal']);
			$_SESSION['modal']['titulo'] = 'Sucesso';
			$_SESSION['modal']['corpo'] = '<p> Sua senha foi alterada! </p>';
			$_SESSION['modal']['tipo'] = 'success';
		}
		catch (Exception $ex) {
			unset($_SESSION['modal']);
			$_SESSION['modal']['titulo'] = 'Erro';
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