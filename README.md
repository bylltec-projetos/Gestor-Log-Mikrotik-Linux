# Gestor-Log-Mikrotik-Linux
Ferramenta para capturar logs de conexões em uma rede provida por um roteador mikrotik e outros (versão Linux).

[Video demonstrativo](https://www.youtube.com/watch?v=wG0e7yudzT0)

### Instalação em Debian 12

#### Atualizar lista de pacotes
```
apt-get update
```
#### Instalar o apache
```
apt-get install -y apache2 
```
#### Instalar o php e suas extensões
```
apt-get install php8.2 php8.2-mbstring php8.2-mysql php8.2-xml -y
```
#### Habilitar a extensão pdo_mysql do php
```
sed -i 's|;extension=pdo_mysql|extension=pdo_mysql|g' /etc/php/8.2/apache2/php.ini
```
#### Instalar mariadb
```
apt-get install mariadb-server -y
```
#### Executar o script de instalação segura do mariadb
```
mysql_secure_installation
```
#### Logar no mariadb
```
mysql -u root -p
```
#### Criar um usuario no mariadb para o sistema/site
```
CREATE USER 'gestorlog'@'localhost' IDENTIFIED BY '@#gestorlog';
```
#### Atribuir privilegios ao novo usuario
```
GRANT ALL PRIVILEGES ON * . * TO 'gestorlog'@'localhost';
```
#### Executar o comando flush privileges para as mudanças entrar em vigor imediatamente
```
FLUSH PRIVILEGES;
```
#### Sair do mariadb
```
exit
```
#### Instalar rsyslog e git
```
apt-get install -y rsyslog-mysql git
```
#### Instalar phpmyadmin
```
apt-get install -y phpmyadmin
```
#### Clone do repositório
```
cd /var/www/html/
git clone https://github.com/bylltec-projetos/Gestor-Log-Mikrotik-Linux.git
```
#### Configuração do rsyslog.conf
```
sed -i 's/#module(load="imudp")/module(load="imudp")/' /etc/rsyslog.conf
sed -i 's/#input(type="imudp" port="514")/input(type="imudp" port="514")/' /etc/rsyslog.conf
sudo systemctl restart systemd-journald
```

#### Configuração do apache2
```
sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/Gestor-Log-Mikrotik-Linux|' /etc/apache2/sites-available/000-default.conf
systemctl restart apache2
```
#### Importação das tabelas do banco de dados
```
cd /var/www/html/Gestor-Log-Mikrotik-Linux
mysql -p Syslog < usuarios.sql
mysql -p Syslog < usuario_log.sql
```
#### Configuração do arquivo site.php
```
sed -i 's/\$username = ""/\$username = "gestorlog"/' /var/www/html/Gestor-Log-Mikrotik-Linux/site/Connections/site.php
sed -i 's/\$password = ""/\$password = "@#gestorlog"/' /var/www/html/Gestor-Log-Mikrotik-Linux/site/Connections/site.php
```
#### Permissões de pasta
```
chmod -R 775 /var/www/html/Gestor-Log-Mikrotik-Linux
```
#### Agendar no crontab para o php execultar o backup a cada 2 hora comando abaixo e adicionando a linha conforme necessidade
```
crontab -e
```
```
0 */2 * * * php /var/www/html/Gestor-Log-Mikrotik-Linux/site/gestorserver/log/backuplog/action_backup_agendado.php
```

#### Agendamento do backup no crontab todo domingo
#echo "0 0 * * 0 php /var/www/html/Gestor-Log-Mikrotik-Linux/site/gestorserver/log/backuplog/action_backup_agendado.php" >> /etc/crontab

#### Configuração do backup diário
```
mkdir /var/backups/gestorlog
chmod -R 775 /var/backups/gestorlog
mkdir /var/backups/gestorlog/diario
chmod -R 775 /var/backups/gestorlog/diario
```
#### Criação do script de backup diário
```
cat <<EOF > /var/backups/gestorlog/backup_diario.sh
#!/bin/bash
ORIGEM_PASTA=/var/backups/gestorlog/diario
DATA=\$(date +"%m-%d-%Y")
ARQUIVO_DESTINO="/var/backups/gestorlog/\$DATA.tar.gz"
echo "Backup está sendo gerado em \$ARQUIVO_DESTINO, por favor aguarde..."
/bin/tar -czvf \$ARQUIVO_DESTINO \$ORIGEM_PASTA
#### Apaga backup após compactar
rm /var/backups/gestorlog/diario/*
#### Reinicia syslog e limpa dados antigos de log
journalctl --rotate
sudo systemctl restart systemd-journald
EOF
```
#### Permissões do script de backup diário
```
chmod +x /var/backups/gestorlog/backup_diario.sh
```
#### Agendamento do backup diário no crontab
```
crontab -e
```
Adicione a linha abaixo caso queira que seja feito todo dia as 1 da manhã
```
0 1 * * * /bin/bash /var/backups/gestorlog/backup_diario.sh
```
