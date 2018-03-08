<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span> 
			</button>
			<a class="navbar-brand" href="index.php"> Repositório </a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">
				<li class="<?php if ($link_active == "h") echo "active"; ?>"><a href="index.php">Início</a></li>
				<li class="<?php if ($link_active == "e") echo "active"; ?>"><a href="artigoAvaliar.php">Avaliar Artigos</a></li>
				<li class="<?php if ($link_active == "d") echo "active"; ?>"><a href="professorCadastro.php">Editar Cadastro</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<a href="logout.php" class="btn btn-danger">
						<span class="glyphicon glyphicon-log-out"></span> Sair 
					</a>
				</li>
			</ul>
		</div>
	</div>
</nav>