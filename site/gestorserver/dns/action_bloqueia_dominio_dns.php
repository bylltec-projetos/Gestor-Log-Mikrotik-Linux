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

    // Configurações para o bloqueio do domínio
    $blockZoneConfig = 'zone "' . $domain . '" {
                           type master;
                           file "/etc/bind/db.blocked";
                       };';

    $blockZoneFile = '$TTL 1H
                       @       IN      SOA     localhost. root.localhost. (
                                       1        ; Serial
                                       1H       ; Refresh
                                       15M      ; Retry
                                       1W       ; Expire
                                       1H )     ; Negative Cache TTL

                       @       IN      NS      localhost.
                       @       IN      A       127.0.0.1
                       *       IN      A       127.0.0.1';

    // Adiciona a configuração da zona de bloqueio ao arquivo de configuração do BIND9
    file_put_contents('/etc/bind/named.conf.local', $blockZoneConfig, FILE_APPEND);

    // Cria o arquivo de zona de bloqueio
    file_put_contents('/etc/bind/db.blocked', $blockZoneFile);

    // Reinicia o serviço BIND9
    exec('sudo service bind9 restart');

    echo 'O domínio ' . $domain . ' foi bloqueado com sucesso.';
} else {
    echo 'Nenhum domínio foi fornecido.';
}
?>
