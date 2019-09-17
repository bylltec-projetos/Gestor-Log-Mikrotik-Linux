
<?php
require_once'/var/www/html/Gestor-Log-Mikrotik-Linux/site/Connections/site.php';
mysql_select_db($database_site, $site);
$data_hora = date("YmdHis");

//$comando = "mysqldump -u $username_site -p$password_site Syslog > /var/www/html/Gestor-Log-Mikrotik-Linux/site/gestorserver/log/backuplog/$data_hora.sql";
//echo $comando;
$comando = "mysqldump -u $username_site -p$password_site Syslog > /var/backups/gestorlog/diario/$data_hora.sql";

$output = shell_exec($comando);
//echo $output;

//echo $comando_compactar = "cd /var/www/html/Gestor-Log-Mikrotik-Linux/site/gestorserver/log/backuplog/ ;/bin/tar -czvf mysql.bkp_data_hora.tar.gz data_hor$

//$comando_compactar = ('ls -lart');
$saida_comando_compactar = shell_exec("cd /var/backups/gestorlog/diario/;
 /bin/tar -czvf $data_hora.tar.gz $data_hora.sql; rm $data_hora.sql");



//require '../../../Connections/site.php';
//mysql_select_db($database_site, $site);

$sql_limpa_tabela = "TRUNCATE TABLE SystemEvents";

mysql_query($sql_limpa_tabela) or die ("Erro ao truncate contate o administrador.");

$saida_comando_limpar_arquivos = shell_exec("cd /var/log/; rm syslog*; rm messages*; rm user*; /etc/init.d/apache2 restart; /etc/init.d/mysql restart; /etc/init.d/syslog restart;");


//para colocar o agendamento use a linha abaixo com o comando nano /etc/crontab
//0 3 * * * root php /var/www/html/Gestor-Log-Mikrotik-Linux/site/gestorserver/log/backuplog/action_backup_agendado.php



//echo "<script type='text/javascript'> alert('Banco de dados de Log foi limpo e liberado espaço de armazenamento.');</script>";
//echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>';

//echo $data_hora = date("Ymd H:i:s");

//$output = shell_exec('mysql -u root -pAngola2002 DELETE FROM SystemEvents');

//$output = shell_exec('ls -lart');
//echo "<pre>$output</pre>";


//echo "<pre>$saida_comando_compactar</pre>";
//echo $saida_comando_compactar;
/*

//exit;
//$output = shell_exec('ls -lart');
echo "<pre>$output</pre>";
*/
//echo "<script type='text/javascript'> alert('Backup realizado com data e hora atual confira na lista no menu status do servidor o novo arquivo criado.');<$
//echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>';

?>
