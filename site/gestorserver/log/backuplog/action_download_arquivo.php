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
// Define o tempo máximo de execução em 0 para as conexões lentas
set_time_limit(0);

// Arqui você faz as validações e/ou pega os dados do banco de dados
//$aquivoNome = 'imagem.jpg'; // nome do arquivo que será enviado p/ download
echo $arquivoNome = $_REQUEST["arquivo"];
echo $arquivoLocal = '/var/www/html/site/gestorserver/log/backuplog/'.$arquivoNome; // caminho absoluto do arquivo
// Verifica se o arquivo não existe
if (!file_exists($arquivoLocal)) {
// Exiba uma mensagem de erro caso ele não exista
  echo "Erro<br/>";
exit;
}
// Aqui você pode aumentar o contador de downloads
// Definimos o novo nome do arquivo
//$novoNome = 'imagem_nova.jpg';
// Configuramos os headers que serão enviados para o browser
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="'.$arquivoNome.'"');
header('Content-Type: application/octet-stream');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($arquivoNome));
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Expires: 0');
// Envia o arquivo para o cliente
ob_end_clean(); //essas duas linhas antes do readfile
flush();
readfile($arquivoNome);

