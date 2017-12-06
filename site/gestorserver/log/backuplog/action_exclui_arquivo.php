<?php
// A sess�o precisa ser iniciada em cada p�gina diferente
if (!isset($_SESSION)) session_start();
$nivel_necessario = 5;
// Verifica se n�o h� a vari�vel da sess�o que identifica o usu�rio
if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel'] > $nivel_necessario)) {
	// Destr�i a sess�o por seguran�a
	session_destroy();
	// Redireciona o visitante de volta pro login
	header("Location: /site/login/index.php"); exit;
}
?>
<?php
$arquivoNome = $_REQUEST["arquivo"];
if(unlink($arquivoNome)){
	echo "Arquivo excluido com sucesso.";
}
else{
	echo "Erro, ontate o administrador";
	exit;
}
echo "<script type='text/javascript'> alert('Arquivo excluido corretamente e espaço de armazenamento liberado.');</script>"; 
echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>';
?>