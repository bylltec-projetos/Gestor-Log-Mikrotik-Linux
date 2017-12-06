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
require '../../../Connections/site.php';
mysql_select_db($database_site, $site);
 
$sql_limpa_tabela = "TRUNCATE TABLE SystemEvents";

mysql_query($sql_limpa_tabela) or die ("Erro ao truncate contate o administrador.");



echo "<script type='text/javascript'> alert('Banco de dados de Log foi limpo e liberado espaço de armazenamento.');</script>"; 
echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>';

//echo $data_hora = date("Ymd H:i:s");

//$output = shell_exec('mysql -u root -pAngola2002 DELETE FROM SystemEvents');

//$output = shell_exec('ls -lart');
//echo "<pre>$output</pre>";
?>