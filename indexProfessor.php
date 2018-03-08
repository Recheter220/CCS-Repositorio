<?php
	$corpo_modal = "";
	
	$restrito = true;

	if ($_SESSION['user']['tipo'] == 'Professor') {
		$restrito = false;
	}
	if ($restrito) {
		$conn->close();
		unset($_SESSION['modal']);
		$_SESSION['modal']['titulo'] = "Acesso restrito";
		$_SESSION['modal']['corpo'] = "A página que você tentou acessar não está disponível para seu nível de usuário. Você foi redirecionado à página inicial.";
		$_SESSION['modal']['tipo'] = 'info';
		ob_end_clean();
		header('Location: index.php');
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
	</script>
<?php
	$link_active = "h";
	require("include/body_begin.php");
?>
	<div class="div-frame">
		<h1> Bem vindo, <?php echo $_SESSION['user']['nome']; ?>! </h1>
	</div>
<?php 
	require_once('include/html_end.php');
	require_once('include/page_end.php');
?>