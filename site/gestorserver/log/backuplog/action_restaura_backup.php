<?php
/*
restaurar backup restor log

descompactar
tar -xvzf 20200526165942.tar.gz -C /var/backups/gestorlog/diario/
salvar no banco de dados
mysql -u root -pbitzer-17 --database=Syslog < 20200526164621.sql
*/

require_once '/var/www/html/Gestor-Log-Mikrotik-Linux/site/Connections/site.php';

$data_hora = date("YmdHis");

//pegar o nome do arquivo que quer restaurar
$arquivoNome = $_REQUEST["arquivo"];

$extensao = substr($arquivoNome, -6);
if ($extensao == "tar.gz" || $extensao == "TAR.GZ") {
    $arquivo_sql = substr($arquivoNome, 0, -7);
    $arquivo = $arquivo_sql . ".sql";

    //Efetuando backup antes de restaurar
    $comando = "mysqldump -u $username_site -p$password_site Syslog > /var/backups/gestorlog/diario/$data_hora.sql";
    $output = shell_exec($comando);
    $saida_comando_compactar = shell_exec("cd /var/backups/gestorlog/diario/; /bin/tar -czvf $data_hora.tar.gz $data_hora.sql; rm $data_hora.sql");

    //limpa base de dados
    $sql_limpa_tabela = "TRUNCATE TABLE SystemEvents";
    $stmt = $pdo->prepare($sql_limpa_tabela);
    $stmt->execute();

    //limpar arquivos desnecessarios e reinicia servicos
    $saida_comando_limpar_arquivos = shell_exec("cd /var/log/; rm syslog*; rm messages*; rm user*; /etc/init.d/apache2 restart; /etc/init.d/mysql restart; /etc/init.d/rsyslog restart;");
    //descompactar e restaurar
    $comando_descompacta_db =  "cd /var/backups/gestorlog/diario/; /bin/tar -xvzf $arquivoNome -C /var/backups/gestorlog/diario/";
    $saida_descompacta_db = shell_exec($comando_descompacta_db);
    $comando_restaura_db = "mysql -u $username_site -p$password_site Syslog < /var/backups/gestorlog/diario/$data_hora.sql";
    $saida_restaura_db = shell_exec($comando_restaura_db);
} else {
    echo "Erro, Contate o administrador 2";
    exit;
}
?>
