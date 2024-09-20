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
// Caminho para o arquivo de configuração do BIND9
$configFile = '/etc/bind/named.conf.local';

// Ler o conteúdo do arquivo de configuração
$configContent = file_get_contents($configFile);

// Expressão regular para extrair os domínios bloqueados
$pattern = '/zone "(.*?)"/';
preg_match_all($pattern, $configContent, $matches);

// Lista de domínios bloqueados
$blockedDomains = $matches[1];

// Verifica se há domínios bloqueados
if (!empty($blockedDomains)) {
    echo "Domínios bloqueados:\n";
    foreach ($blockedDomains as $domain) {
        echo $domain . "\n";
    }
} else {
    echo "Não há domínios bloqueados.";
}
?>
