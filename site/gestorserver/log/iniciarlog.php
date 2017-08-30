<?php
// A sessï¿½o precisa ser iniciada em cada pï¿½gina diferente
if (!isset($_SESSION)) session_start();
$nivel_necessario = 5;
// Verifica se nï¿½o hï¿½ a variï¿½vel da sessï¿½o que identifica o usuï¿½rio
if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel'] > $nivel_necessario)) {
	// Destrï¿½i a sessï¿½o por seguranï¿½a
	session_destroy();
	// Redireciona o visitante de volta pro login
	header("Location: /site/login/index.php"); exit;
}
?>
<?php

 $DiretorioLigar = "MT_Syslog.exe";

function Ligar ($Lugar) {

   // chdir($Lugar); 

    $call = $Lugar;

    pclose(popen('start /b '.$call.'', 'r')); 

}

Ligar($DiretorioLigar); //Função.
//taskkill /f /im MT_Syslog.exe
//echo exec('MT_Syslog.exe');
?>
