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
$arquivoNome = "/var/backups/gestorlog/".$_REQUEST["arquivo"];

$extensao = substr($arquivoNome, -6); 
if($extensao == "tar.gz" or $extensao == "TAR.GZ"){

	if(unlink($arquivoNome)){
		echo "Arquivo excluido com sucesso.";
	}
	else{
		echo "Erro, Contate o administrador 1";
		exit;
	}
}else{
	echo "Erro, Contate o administrador 2";
	exit;

}
echo "<script type='text/javascript'> alert('Arquivo excluido corretamente e espaÃ§o de armazenamento liberado.');</script>"; 
echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>';
?>
