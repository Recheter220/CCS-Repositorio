 	</head>
	<body>	
<?php 
	if (isset($_SESSION["user"])) {
		if ($_SESSION["user"]["tipo"] == "Administrador") {
			require("include/navbar_admin.php"); 
		}
		elseif ($_SESSION["user"]["tipo"] == "Professor") {
			require("include/navbar_professor.php"); 
		}
		elseif ($_SESSION["user"]["tipo"] == "Aluno") {
			require("include/navbar_aluno.php"); 
		}
	}
?>