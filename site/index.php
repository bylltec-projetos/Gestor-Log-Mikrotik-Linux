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

