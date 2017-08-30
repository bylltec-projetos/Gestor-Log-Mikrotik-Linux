<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_site = "localhost";
$database_site = "gestorlog";
$username_site = "log";
$password_site = "log";
$site = mysql_pconnect($hostname_site, $username_site, $password_site) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
<?php set_time_limit(0); ?>
<?php



//mysql_select_db($database_site, $site);
$iduser = $_SESSION['iduser'];

//Mar/05/2014 18:45:38 web-proxy,account logproxydisk: 192.168.5.1 GET http://img.ibxk.com.br/2014/03/05/220.jpg  action=allow cache=MISS
date_default_timezone_set('America/Campo_Grande');
//veja abaixo a lista de timezones e edite de acordo com sua localidade
//http://www.php.net/manual/pt_BR/timezones.america.php
$script_tz = date_default_timezone_get();

if (strcmp($script_tz, ini_get('date.timezone'))){
    echo 'Script timezone differs from ini-set timezone.';
} else {
    echo 'Script timezone and ini-set timezone match.';
}

function valida_dominio($dominio){
  if(strstr($dominio, "www"))
 list($user, $dominio) = explode("www.", $dominio);
  if(checkdnsrr($dominio,"MX")){
     return 'Dominio valido';
  }else{
     return 'Dominio invalido';
  }
}
//schtasks /create /sc minute /mo 20 /tn "Script de segurança" /tr \\central\data\scripts\sec.vbs 
//$agendatarefa = shell_exec('schtasks /create /sc minute /mo 20 /tn "Script Gestor Log" /tr "C:\Program Files\Internet Explorer\IEXPLORE.EXE "http://localhost/site/log/log.php"');
//echo "<pre>$agendatarefa</pre>";
//parar log 
$output = shell_exec('taskkill /f /im MT_Syslog.exe');
echo "<pre>$output</pre>";
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

Ligar($DiretorioLigar); //Função inicia log

$arquivo = fopen("backuplog/GestorLog$data$hora.log",'r');
if ($arquivo == false) die('Naoo foi possivel abrir o arquivo.');
while(true) {
	$linha = fgets($arquivo);
	if ($linha==null) break;
	echo $linha;
        echo "<br>";
        
$posicao = strpos($linha, 'http://');
//echo $posicao;
//echo "<br>";
$texto= substr($linha, $posicao+7); // pablo.blog.br

//echo "<br>";

$string=explode("/", $texto);

$statusdominio= valida_dominio($string[0]);
if ($statusdominio=="Dominio valido"){
    
echo"$string[0]";//Este
$dominiovalido = $string[0];
echo "<br>";
echo strlen($linha); // 14
echo "<br>";
echo substr_count($linha, ' '); // 2
echo "<br>";
$parteslog=explode(" ", $linha);
//$data = $parteslog[0];
//$horalog=$parteslog[1];
//$datadb = DateTime::createFromFormat('M/d/Y', $data);
//$datalog= $datadb->format('Y-m-d');
$horalog=date("H:i:s");
$datalog=date("Y-m-d");
$sqlloglinha = "SELECT * FROM `log` WHERE `linhalog` = '$linha' and `tipo`='Dominio' ";

$queryloglinha = mysql_query($sqlloglinha);
if (mysql_num_rows($queryloglinha) <= 0) {
		

$sqladicionalog = "INSERT INTO `$database_site`.`log` (`idlog`, `dominio`, `linhalog`, `iduser`, `idservidor`,`tipo`,`data`,`hora`) VALUES (NULL, '$dominiovalido', '$linha', '2', '54','Dominio', '$datalog','$horalog');";

$query = mysql_query($sqladicionalog);
}
}
 else {
  $sqladicionalog = "INSERT INTO `$database_site`.`log` (`idlog`, `dominio`, `linhalog`, `iduser`, `idservidor`,`tipo`,`data`,`hora`) VALUES (NULL, '$dominiovalido', '$linha', '2', '54','Outros', '$datalog','$horalog');";

$query = mysql_query($sqladicionalog);  
}
}
fclose($arquivo);
//<html>
//<meta http-equiv="refresh" content="1;URL=/site/gestorserver/log/?pagina=status">
//</html>
$fechaiexplore = shell_exec('taskkill /f C:\Program Files\Internet Explorer\iexplore.exe');
echo "<pre>$fechaiexplore</pre>";
//die("<script> window.close() </script>");
?>
