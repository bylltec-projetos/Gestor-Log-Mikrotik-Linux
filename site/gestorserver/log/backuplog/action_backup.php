<?php
// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION)) session_start();
$nivel_necessario = 5;
// Verifica se não há a variável da sessão que identifica o usuário
if (!isset($_SESSION['UsuarioID']) || ($_SESSION['UsuarioNivel'] > $nivel_necessario)) {
    // Destrói a sessão por segurança
    session_destroy();
    // Redireciona o visitante de volta para o login
    header("Location: /site/login/index.php");
    exit;
}
?>
<?php
require_once '../../../Connections/site.php';
$data_hora = date("YmdHis");
$comando = "mysqldump -u $username_site -p$password_site Syslog > /var/backups/gestorlog/diario/$data_hora.sql";
//echo $comando;
$output = shell_exec($comando);
//echo $output;
$saida_comando_compactar = shell_exec("cd /var/backups/gestorlog/diario/;
 /bin/tar -czvf $data_hora.tar.gz $data_hora.sql; rm $data_hora.sql");
?>
<script type='text/javascript'> alert('Backup realizado com data e hora atual confira na lista no menu status do servidor o novo arquivo criado.');</script>
<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>
