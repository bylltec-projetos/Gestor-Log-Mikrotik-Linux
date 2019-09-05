<?php
 
if (!isset($_SESSION)) session_start();
$nivel_necessario = 5;
// Verifica se n?o h? a vari?vel da sess?o que identifica o usu?rio
if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel'] > $nivel_necessario)) {
	// Destr?i a sess?o por seguran?a
	session_destroy();
	// Redireciona o visitante de volta pro login
	header("Location: /site/login/"); exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Gestor Log</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append �#!watch� to the browser URL, then refresh the page. -->
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="img/favicon.png">
  
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
</head>

<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<nav class="navbar navbar-default" role="navigation">
				<div class="navbar-header">
					 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button> <a class="navbar-brand" href="?pagina=status">Status do Log</a>
				</div>
				
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						
                                                <li>
							<a href="/site/gestorserver/log/?pagina=relatorio">Relatorio</a>
						</li>
						<li>
							<a href="/site/gestorserver/log/?pagina=estatistica">Estatistica</a>
						</li>
                                                <li>
							<a href="/site/gestorserver/log/?pagina=rank">Top Rank</a>
						</li>
						<li class="dropdown">
							 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Configurar<strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li>
									<a href="?pagina=adicionar_usuario">Vincular usuario a IP</a>
								</li>
								<li>
									<a href="?pagina=manutencao">Manutencao do log</a>
								</li>
<!--								<li>
									<a href="https://www.bylltec.com.br/site/home.php?pagina=suporte">Ajudar no desenvolvimento</a>
								</li>
								<li class="divider">
								</li>
								<li>
									<a href="#">Importar arquivo log</a>
								</li>-->
								<li class="divider">
								</li>
								<li>
									<a href="https://www.bylltec.com.br/site/home.php?pagina=manual">Manual</a>
								</li>
<!--                                                                <li>
									<a href="https://www.bylltec.com.br/site/home.php?pagina=manual">Versão Linux</a>
								</li>-->
							</ul>
						</li>
					</ul>
					
					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="https://www.bylltec.com.br/site/home.php?pagina=suporte&sistema=gestorlog">Suporte - Versao 0.0.5</a>
						</li>
						<li>
							<a href="/site/login/logout.php">Sair</a>
						</li>
					</ul>
				</div>
				
			</nav>
                   
                     


                     
			
                             
		</div>
	</div>
	<?php
$pagina = $_REQUEST['pagina'];
$tipografico = $_REQUEST['tipografico'];

if ($pagina ==''){
 
  require('./home.php');
 }
 if ($pagina =='manutencao'){
 
  require('../../admin/redefinir_senha.php');
 }
 if ($pagina =='status'){
 
  require('./status.php');
 }
 if ($pagina =='relatorio'){
 
  require('./relatorio_log.php');
 }
 if ($pagina =='rank'){
 
  require('./top_rank.php');
 }
 
 if ($pagina =='estatistica'){
     if($tipografico==''){
  require('./grafico_dominio_pizza.php');
 }
 if($tipografico=='pizza'){
  require('./grafico_dominio_pizza.php');
 }
 if($tipografico=='funil'){
     require './grafico_funil_usuario.php';
 }
 }
 
 if ($pagina =='adicionar_usuario'){
 
  require('./add_novo_usuario.php');
    require('./usuarios_cadastrados.php');
 }
 
 ?>
</div>
</body>
</html>
