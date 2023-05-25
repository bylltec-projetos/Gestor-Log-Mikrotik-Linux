<?php
require_once '/var/www/html/Gestor-Log-Mikrotik-Linux/site/Connections/site.php';
$data_hora = date("YmdHis");
$comando = "mysqldump -u $username_site -p$password_site Syslog > /var/backups/gestorlog/diario/$data_hora.sql";
$output = shell_exec($comando);
//echo $output;
$saida_comando_compactar = shell_exec("cd /var/backups/gestorlog/diario/;
 /bin/tar -czvf $data_hora.tar.gz $data_hora.sql; rm $data_hora.sql");
$sql_limpa_tabela = "TRUNCATE TABLE SystemEvents";
$stmt_limpa_tabela = $pdo->prepare($sql_limpa_tabela);
$stmt_limpa_tabela->execute();
$saida_comando_limpar_arquivos = shell_exec("cd /var/log/; rm syslog*; rm messages*; rm user*; /etc/init.d/apache2 restart; /etc/init.d/mysql restart; /etc/init.d/rsyslog restart;");
?>
