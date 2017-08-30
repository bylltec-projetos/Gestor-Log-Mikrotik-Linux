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

$nomeusuario= mysql_real_escape_string($_REQUEST["nomeusuario"]);
$ipusuario= mysql_real_escape_string($_REQUEST["ipusuario"]);
$obs= mysql_real_escape_string($_REQUEST["obs"]);

 
$sqlbuscausuario = "SELECT * FROM `usuario_log` WHERE `usuariolog` LIKE '$nomeusuario' ";

$querybuscausuario = mysql_query($sqlbuscausuario) or die ("sql filtro grupo erro");
$row_rslistagrupo = mysql_fetch_assoc($querybuscausuario);
$totalusuariolog = mysql_num_rows($querybuscausuario);

if($nomeusuario==""){
echo "<script type='text/javascript'> alert('Preencha o nome.');</script>";
echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';    
}
elseif($ipusuario==""){
echo "<script type='text/javascript'> alert('Preencha o ip.');</script>";
 echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';    
}
elseif (mysql_num_rows($querybuscausuario) > 0) {
    
  
     echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>'; 
    echo "<script type='text/javascript'> alert('Este usuario ja existe.');</script>";   
 }
 
 else{
    
$sqladicionafiltrogrupo = "INSERT INTO `$database_site`.`usuario_log` (`idusuariolog`,`usuariolog`,`ipusuariolog`,`obs`) VALUES (NULL, '$nomeusuario', '$ipusuario', '$obs')";
//echo $$sql3;
mysql_query($sqladicionafiltrogrupo) or die ("nao foi possivel inserir o grupo");

echo "<script type='text/javascript'> alert('O $nomeusuario adicionado com sucesso.');</script>"; 
echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';   
}

 

?>
