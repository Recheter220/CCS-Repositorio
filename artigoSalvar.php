<?php
	require_once('include/page_begin.php');
	
	$artigo = $_SESSION["artigo"];
	var_dump($_SESSION);

	if ($_SESSION["artigo"]["acao"] == "Salvar") {
		try {
			if ($artigo["id"] == 0) { // Cadastrar novo
				$consulta = "INSERT INTO tb_artigo (aluno_id, art_titulo, art_resumo, art_caminho, art_status)
					VALUES (?, ?, ?, ?, 0)";
				unset($params);
				$params[] = $_SESSION['user']['aluno_id'];
				$params[] = $artigo['titulo'];
				$params[] = $artigo['resumo'];
				$params[] = $artigo['caminho'];
				$tipos = "isss";
				SqlExecutar($conn, $consulta, $params, $tipos);

				unset($_SESSION['modal']);
				$_SESSION['modal']['titulo'] = 'Sucesso';
				$_SESSION['modal']['corpo'] = '<p> Seu artigo foi carregado para análise. Dentro de alguns dias você terá resposta de sua aprovação ou não para publicação. </p>';
				$_SESSION['modal']['tipo'] = 'success';
			}
			else { // Atualizar existente
				if (!empty($artigo['caminho'])) {
					$consulta = "UPDATE tb_artigo a
						SET 
						a.art_titulo = ?,
						a.art_resumo = ?,
						a.art_caminho = ?
						WHERE a.art_id = ?
						AND a.aluno_id = ?";

					unset($params);
					$params[] = $artigo['titulo'];
					$params[] = $artigo['resumo'];
					$params[] = $artigo['caminho'];
					$params[] = $artigo['id'];
					$params[] = $_SESSION['user']['aluno_id'];
					$tipos = "sssii";
					SqlExecutar($conn, $consulta, $params, $tipos);
				}
				else {
					$consulta = "UPDATE tb_artigo a
						SET 
						a.art_titulo = ?,
						a.art_resumo = ?
						WHERE a.art_id = ?
						AND a.aluno_id = ?";

					unset($params);
					$params[] = $artigo['titulo'];
					$params[] = $artigo['resumo'];
					$params[] = $artigo['id'];
					$params[] = $_SESSION['user']['aluno_id'];
					$tipos = "ssii";
					SqlExecutar($conn, $consulta, $params, $tipos);
				}

				unset($_SESSION['modal']);
				$_SESSION['modal']['titulo'] = 'Sucesso';
				$_SESSION['modal']['corpo'] = '<p> Seu artigo foi carregado para análise. Dentro de alguns dias você terá resposta de sua aprovação ou não para publicação. </p>';
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
	elseif ($_SESSION['artigo']['acao'] == 'Apagar') {
		try {
			$consulta = "DELETE FROM tb_artigo 
				WHERE aluno_id = ?
				AND art_id = ?";
			unset($params);
			$params[] = $_SESSION['artigo']['aluno_id'];
			$params[] = $_SESSION['artigo']['id'];
			$tipos = 'ii';
			SqlExecutar($conn, $consulta, $params, $tipos);
			
			unset($_SESSION['modal']);
			$_SESSION['modal']['titulo'] = 'Sucesso';
			$_SESSION['modal']['corpo'] = '<p> O artigo foi removido do sistema! </p>';
			$_SESSION['modal']['tipo'] = 'success';
		}
		catch (Exception $ex) {
			unset($_SESSION['modal']);
			$_SESSION['modal']['titulo'] = 'Ocorreu um erro';
			$_SESSION['modal']['corpo'] = '<p> Não foi possível apagar o artigo devido a um erro no sistema. <br /> Detalhes: '. nl2br2($ex->getMessage()) .'</p>';
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