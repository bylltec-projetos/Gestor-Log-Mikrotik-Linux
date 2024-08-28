<?php
if (!isset($_SESSION)) {
    session_start();
}
$nivel_necessario = 5;
if (!isset($_SESSION['UsuarioID']) || ($_SESSION['UsuarioNivel'] > $nivel_necessario)) {
    session_destroy();
    header("Location: /site/login/index.php");
    exit;
}
?>
<?php
// Verifica se um domínio foi fornecido
if (isset($_REQUEST['domain'])) {
    $domain = $_REQUEST['domain'];

    // Escapa caracteres especiais do domínio para evitar injeção de comandos
    $domain = escapeshellcmd($domain);

    // Remove a configuração da zona de bloqueio do arquivo de configuração do BIND9
    $configFile = '/etc/bind/named.conf.local';
    $blockZoneConfig = 'zone "' . $domain . '" {';

    $configContent = file_get_contents($configFile);
    $updatedContent = str_replace($blockZoneConfig, '', $configContent);
    file_put_contents($configFile, $updatedContent);

    // Remove o arquivo de zona de bloqueio
    $blockZoneFile = '/etc/bind/db.blocked';
    if (file_exists($blockZoneFile)) {
        unlink($blockZoneFile);
    }

    // Reinicia o serviço BIND9
    exec('sudo service bind9 restart');

    echo 'O domínio ' . $domain . ' foi desbloqueado com sucesso.';
} else {
    echo 'Nenhum domínio foi fornecido.';
}
?>
