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
<?php set_time_limit(0); ?>
<?php

require_once('../../Connections/site.php');

//mysql_select_db($database_site, $site);
$iduser = $_SESSION['iduser'];

//Mar/05/2014 18:45:38 web-proxy,account logproxydisk: 192.168.5.1 GET http://img.ibxk.com.br/2014/03/05/220.jpg  action=allow cache=MISS
date_default_timezone_set('America/Campo_Grande');
//veja abaixo a lista de timezones e edite de acordo com sua localidade
//http://www.php.net/manual/pt_BR/timezones.america.php
//$script_tz = date_default_timezone_get();
//
//if (strcmp($script_tz, ini_get('date.timezone'))){
//    echo 'Script timezone differs from ini-set timezone.';
//} else {
//    echo 'Script timezone and ini-set timezone match.';
//}


//parar log 
$output = shell_exec('taskkill /f /im MT_Syslog.exe');
//echo "<pre>$output</pre>";
sleep(10);
$data = date("Ymd");
$hora = date("His");

$file = 'tmplog.log';
$newfile = "backuplog/GestorLog$data$hora.log";

if (!copy($file, $newfile)) {
    echo "falha ao copiar $file...\n";
    
}
else{
    $mask = "tmplog.log";
   array_map( "unlink", glob( $mask ) );
}
 $DiretorioLigar = "MT_Syslog.exe";

function Ligar ($Lugar) {

   // chdir($Lugar); 

    $call = $Lugar;

    pclose(popen('start /b '.$call.'', 'r')); 

}

Ligar($DiretorioLigar); //Fun��o inicia log

$arquivo = fopen("backuplog/GestorLog$data$hora.log",'r');
if ($arquivo == false) die('Naoo foi possivel abrir o arquivo.');
while(true) {
	$linha = fgets($arquivo);
	if ($linha==null) break;
	//echo $linha;
        //echo "<br>";
        
$posicao = strpos($linha, 'http://');
//echo $posicao;
//echo "<br>";
$texto= substr($linha, $posicao+7); // pablo.blog.br

//echo "<br>";

$string=explode("/", $texto);
   
//echo"$string[0]";//Este
$dominiovalido = $string[0];
//echo "<br>";
//echo strlen($linha); // 14
//echo "<br>";
//echo substr_count($linha, ' '); // 2
//echo "<br>";
$parteslog=explode(" ", $linha);
//$data = $parteslog[0];
//$horalog=$parteslog[1];
//$datadb = DateTime::createFromFormat('M/d/Y', $data);
//$datalog= $datadb->format('Y-m-d');
$horalog=date("H:i:s");
$datalog=date("Y-m-d");
//$sqlloglinha = "SELECT * FROM `log` WHERE `linhalog` = '$linha' and `tipo`='Dominio' ";
//
//$queryloglinha = mysql_query($sqlloglinha);
//if (mysql_num_rows($queryloglinha) <= 0) {
		

$sqladicionalog = "INSERT INTO `$database_site`.`log` (`idlog`, `dominio`, `linhalog`, `tipo`,`data`,`hora`) VALUES (NULL, '$dominiovalido', '$linha', 'Dominio', '$datalog','$horalog');";

$query = mysql_query($sqladicionalog);

  $sqladicionalog = "INSERT INTO `$database_site`.`log` (`idlog`, `dominio`, `linhalog`, `tipo`,`data`,`hora`) VALUES (NULL, '$dominiovalido', '$linha', 'Outros', '$datalog','$horalog');";

$query = mysql_query($sqladicionalog);  

}
fclose($arquivo);

?>
<p class="align-center"><img src="/site/images/progresso.gif" width="30" height="30" alt="progress"/>Aguarde... </p>
<html>
<meta http-equiv="refresh" content="1;URL=/site/gestorserver/log/?pagina=status">
</html>