<?php
	date_default_timezone_set("America/Sao_Paulo");
	
	$local = 1;
	if ($local) {
		/* LOCAL */
		$servername = "localhost"; 
		$dbName = "repositorio";
		$userSelect = "root";
		$userModify = "root";
		$pwSelect = "";
		$pwModify = "";
	}
	else {
		/* SITE */
		$servername = "localhost"; //Alterar para publicacao
		$dbName = "repositorio";
		$userSelect = "root";
		$userModify = "root";
		
		$pwSelect = "";
		$pwModify = "";
	}
	
	$conn = mysqli_connect($servername, $userModify, $pwModify, $dbName);
	$qtdErros = 0;
	while (in_array($conn->errno, array("1040", "1203")) && $qtdErros < 5) {
		$qtdErros++;
		sleep(2);
		$conn = mysqli_connect($servername, $userModify, $pwModify, $dbName);
	}
	if ($qtdErros == 5) {
		die("O site está passando por muitos acessos no momento. Espere alguns instantes e atualize a página no navegador.");
	}
	$conn->set_charset("utf8");
	
	function SqlPesquisar($conexao, $consulta, $params = null, $tipos = "") {
		$a_params = array();
		$resultado = array();
		$n = count($params);
		 
		/* with call_user_func_array, array params must be passed by reference */
		$a_params[] = &$tipos;
		 
		for($i = 0; $i < $n; $i++) {
		  /* with call_user_func_array, array params must be passed by reference */
		  $a_params[] = &$params[$i];
		}
		 
		/* Prepare statement */
		$stmt = $conexao->prepare($consulta);
		if($stmt === false) {
			throw new Exception('Erro na consulta: ' . $consulta . '<br />MySqli error: ' . $conexao->errno . ' ' . $conexao->error);
		}
		
		/* use call_user_func_array, as $stmt->bind_param('s', $param); does not accept params array */
		if ($tipos != "") {
			if (!(call_user_func_array(array($stmt, 'bind_param'), $a_params))) {
				throw new Exception('Erro na atribuição de parâmetros: ' . $consulta . '<br />MySqli error: ' . $conexao->errno . ' ' . $conexao->error);
			}
		}
				
		if (!($stmt->execute())) {
			throw new Exception('Erro na execução do comando: ' . $consulta . '<br />MySqli error: ' . $conexao->errno . ' ' . $conexao->error);
		}
		
		$meta = $stmt->result_metadata(); 
		while ($field = $meta->fetch_field()) 
		{ 
			$campos[] = &$row[$field->name]; 
		}

		call_user_func_array(array($stmt, 'bind_result'), $campos);
		
		while ($stmt->fetch()) { 
			foreach($row as $key => $val) 
			{ 
				$c[$key] = $val; 
			} 
			$resultado[] = $c; 
		}
		
		return $resultado;
	}
	
	function SqlExecutar($conexao, $consulta, $params = null, $tipos = "") {
		$a_params = array();
		$n = count($params);

		$a_params[] = &$tipos;

		for($i = 0; $i < $n; $i++) {
			$a_params[] = &$params[$i];
		}

		$stmt = $conexao->prepare($consulta);
		if($stmt === false) {
			throw new Exception('Erro na consulta: ' . $consulta . '<br />MySqli error: ' . $conexao->errno . ' ' . $conexao->error);
		}

		if ($tipos != "") {
			if (!(call_user_func_array(array($stmt, 'bind_param'), $a_params))) {
				throw new Exception('Erro na atribuição de parâmetros: ' . $consulta . '<br />MySqli error: ' . $conexao->errno . ' ' . $conexao->error);
			}
		}

		if (!($stmt->execute())) {
			throw new Exception('Erro na execução do comando: ' . $consulta . '<br />MySqli error: ' . $conexao->errno . ' ' . $conexao->error);
		}
	}
?>