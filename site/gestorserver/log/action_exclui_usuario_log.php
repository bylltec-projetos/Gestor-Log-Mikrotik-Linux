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
require '../../Connections/site.php';
mysql_select_db($database_site, $site);

$idusuariolog= mysql_real_escape_string($_REQUEST["id"]);
 echo $idusuariolog;
if($idusuariolog==""){
echo "<script type='text/javascript'> alert('Escolha o usuario deseja excluir.');</script>";
 echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';    
}
else{
    
$sqldeletausuario = "DELETE FROM `$database_site`.`usuario_log` WHERE `idusuariolog` = '$idusuariolog' ;";

//verifica se foi inserido corretamente os dados na tabela
mysql_query($sqldeletausuario) or die ("erro");

echo "<script type='text/javascript'> alert('Usuario excluido com sucesso.');</script>"; 
echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';   
}

 



?>
