<?php
	$consulta = 'SELECT adm_id, adm_nome, FROM tb_admin WHERE user_id = ?';
	unset($params);
	$params[] = $_SESSION["user"]["id"];
	$tipos = "i";
	$result = SqlPesquisar($conn, $consulta, $params, $tipos);
	$admin = array();
	if (count($result) == 1) {
		$row = $result[0];
		$admin = array("id" => $row["adm_id"],
						 "nome" => $row["adm_nome"]);
	}
	else {
		echo "O usuário não está relacionado a nenhum administrador.";
		$conn->close();
		die("<a href='logout.php'> Sair </a>");
	}
?>
<?php
	require("include/html_begin.php");	
?>
	<!-- Inserir aqui o conteúdo personalizado de HTML <head> -->
	<script type="text/javascript">
		function download(path) {
			window.open(path, "_blank");
		}
		
		carregando = 0;
		function expandir(tipo, id, btn) {
			if (carregando == 0) {
				carregando = 1;
				var xhttp = new XMLHttpRequest();
				var tabela = document.getElementById("docs-"+id);
				if (tabela.innerHTML == "") {
					xhttp.onreadystatechange = function() {
						if (xhttp.readyState == 4 && xhttp.status == 200) {						
							tabela.innerHTML = xhttp.responseText;
							$("#docs-"+id).slideDown();
							btn.innerHTML = "<span class='glyphicon glyphicon-collapse-up'> </span> Recolher";
							carregando = 0;
						}
					};
					xhttp.open("GET", ("ajax/carregaArtigos.php?tipo=" + tipo + "&id=" + id), true);
					xhttp.send();
				}
				else {
					if (tabela.style.display == "none") {
						$("#docs-"+id).slideDown();
						btn.innerHTML = "<span class='glyphicon glyphicon-collapse-up'> </span> Recolher";
						carregando = 0;
					}
					else {
						$("#docs-"+id).slideUp();
						btn.innerHTML = "<span class='glyphicon glyphicon-collapse-down'> </span> Expandir";
						carregando = 0;
					}
				}
			}
		}
	</script>
<?php
	$link_active = "h";
	require("include/body_begin.php");
?>
	<div class="div-frame">
		<h1> Bem vindo, <?php echo $admin["nome"]; ?>! </h1>
	</div>
<?php 
	require_once('include/html_end.php');
	require_once('include/page_end.php');
?>