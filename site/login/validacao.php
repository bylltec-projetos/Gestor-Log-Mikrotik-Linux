<?php

// Verifica se houve POST e se o usu�rio ou a senha �(s�o) vazio(s)
if (!empty($_POST) AND (empty($_POST['usuario']) OR empty($_POST['senha']))) {
	header("Location: index.php"); exit;
}
include('../Connections/site.php');//dados do servidor requerido

// Tenta se conectar ao servidor MySQL
mysql_connect("$hostname_site", "$username_site", "$password_site") or trigger_error(mysql_error());
// Tenta se conectar a um banco de dados MySQL
mysql_select_db("$database_site") or trigger_error(mysql_error());

$usuario = mysql_real_escape_string($_POST['usuario']);
$senha = mysql_real_escape_string($_POST['senha']);

// Valida��o do usu�rio/senha digitados
$sql = "SELECT `iduser`, `nome`, `nivel` FROM `usuarios` WHERE (`usuario` = '". $usuario ."') AND (`senha` = '". sha1($senha) ."') AND (`status` = 'ativo') LIMIT 1";
$query = mysql_query($sql);
if (mysql_num_rows($query) != 1) {
	// Mensagem de erro quando os dados s�o inv�lidos e/ou o usu�rio n�o foi encontrado
     echo '<head><meta http-equiv=refresh content="0; URL=/site/login/index.php"></head>';
  echo "<script type='text/javascript'> alert('Login invalido!');</script>";
	//echo "Login inv�lido!";
        exit;
} else {
	// Salva os dados encontados na vari�vel $resultado
	$resultado = mysql_fetch_assoc($query);

	// Se a sess�o n�o existir, inicia uma
	if (!isset($_SESSION)) session_start();

	// Salva os dados encontrados na sess�o
	$_SESSION['UsuarioID'] = $resultado['iduser'];
	$_SESSION['UsuarioNome'] = $resultado['nome'];
	$_SESSION['UsuarioNivel'] = $resultado['nivel'];
	$_SESSION['iduser'] = $resultado['iduser'];
	//aviso ao usuario
        
	// Redireciona o visitante
	header("Location: /site/gestorserver/log/?pagina=status"); exit;
        
}

?>

