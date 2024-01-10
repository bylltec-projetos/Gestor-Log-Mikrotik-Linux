# Gestor-Log-Mikrotik-Linux
Ferramenta para capturar logs de conexões em uma rede provida por um mikrotik (versão Linux).

[Video demonstrativo](https://www.youtube.com/watch?v=wG0e7yudzT0)

### Instalação em Debian 12

#### atualizar lista de pacotes
apt-get update

#### instalar o apache
apt-get install -y apache2 

#### instalar o php e suas extensões
apt-get install php8.2 php8.2-mbstring php8.2-mysql php8.2-xml -y

#### habilitar a extensão pdo_mysql do php
sed -i 's|;extension=pdo_mysql|extension=pdo_mysql|g' /etc/php/8.2/apache2/php.ini

#### instalar mariadb
apt-get install mariadb-server -y

#### executar o script de instalação segura do mariadb
mysql_secure_installation

#### logar no mariadb
mysql -u root -p

#### criar um usuario no mariadb para o sistema/site
CREATE USER 'gestorlog'@'localhost' IDENTIFIED BY '@#gestorlog';

#### atribuir privilegios ao novo usuario
GRANT ALL PRIVILEGES ON * . * TO 'gestorlog'@'localhost';

#### executar o comando flush privileges para as mudanças entrar em vigor imediatamente
FLUSH PRIVILEGES;

#### sair do mariadb
exit

####instalar rsyslog e git
apt-get install -y rsyslog-mysql git

####instalar phpmyadmin
apt-get install -y phpmyadmin

#### Clone do repositório
cd /var/www/html/
git clone https://github.com/bylltec-projetos/Gestor-Log-Mikrotik-Linux.git

#### Configuração do rsyslog.conf
sed -i 's/#module(load="imudp")/module(load="imudp")/' /etc/rsyslog.conf
sed -i 's/#input(type="imudp" port="514")/input(type="imudp" port="514")/' /etc/rsyslog.conf
sudo systemctl restart systemd-journald


#### Configuração do apache2
sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/Gestor-Log-Mikrotik-Linux|' /etc/apache2/sites-available/000-default.conf
systemctl restart apache2

#### Importação das tabelas do banco de dados
cd /var/www/html/Gestor-Log-Mikrotik-Linux
mysql -p Syslog < usuarios.sql
mysql -p Syslog < usuario_log.sql

#### Configuração do arquivo site.php
sed -i 's/\$username = ""/\$username = "gestorlog"/' /var/www/html/Gestor-Log-Mikrotik-Linux/site/Connections/site.php
sed -i 's/\$password = ""/\$password = "@#gestorlog"/' /var/www/html/Gestor-Log-Mikrotik-Linux/site/Connections/site.php

#### Permissões de pasta
chmod -R 775 /var/www/html/Gestor-Log-Mikrotik-Linux

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
#### Reinicia syslog
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

#### Instação em Debian 9
#Caso você use o APT, adicione a seguinte linha em /etc/apt/sources.list 

```
deb http://ftp.debian.org/debian/ jessie main contrib non-free
deb-src http://ftp.debian.org/debian/ jessie main contrib non-free
deb http://security.debian.org/ jessie/updates main contrib non-free
deb-src http://security.debian.org/ jessie/updates main contrib non-free 
```
```
apt-get install apache2 mysql-server php5 php5-mysql phpmyadmin
apt-get install rsyslog-mysql
apt-get install git
```
Caminhe ate a pasta do apache cd /var/www/html/ depois faz o clone do repositorio do gestor log linux
```
cd /var/www/html/
git clone https://github.com/bylltec-projetos/Gestor-Log-Mikrotik-Linux.git
```
Também é necessário descomentar as linhas abaixo no arquivo /etc/rsyslog.conf do servidor:
```
nano /etc/rsyslog.conf
```
```
#provides UDP syslog reception 
$ModLoad imudp
$UDPServerRun 514
```
//reiniciar rsyslog
```
service rsyslog restart 
```
Definir o diretorio raiz para o apache2 caso necessario normalmente em /etc/apache2/sites-available/000-default.conf modificando a linha para /var/www/html/Gestor-Log-Mikrotik-Linux   
```
nano /etc/apache2/sites-available/000-default.conf
```
Exemplo abaixo de como deve ficar 
```
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/Gestor-Log-Mikrotik-Linux
</VirtualHost>
```
Reiniciar o apache
```
service apache2 restart
```
Estando dentro da pasta do sistema importar as tabelas de usuario
exemplo root@debian:/var/www/html/Gestor-Log-Mikrotik-Linux# mysql -p Syslog < usuarios.sql 
exemplo root@debian:/var/www/html/Gestor-Log-Mikrotik-Linux# mysql -p Syslog < usuario_log.sql 
```
cd /var/www/html/Gestor-Log-Mikrotik-Linux
mysql -p Syslog < usuarios.sql
mysql -p Syslog < usuario_log.sql
```
Editar o arquivo definindo o login e senha do banco de dados
Exemplo root@debian:/var/www/html/Gestor-Log-Mikrotik-Linux# nano site/Connections/site.php
```
nano /var/www/html/Gestor-Log-Mikrotik-Linux/site/Connections/site.php
```
Permissoes conforme necessidade na pasta do projeto /var/www/html/Gestor-Log-Mikrotik-Linux
```
chmod -R 775 /var/www/html/Gestor-Log-Mikrotik-Linux
```
Agendar no crontab para o php execultar o backup a cada 2 hora comando abaixo e adicionando a linha conforme necessidade
```
crontab -e
```
```
0 */2 * * * php /var/www/html/Gestor-Log-Mikrotik-Linux/site/gestorserver/log/backuplog/action_backup_agendado.php
```
Criar uma pasta para o backup e dar as devidas permissao de leitura e escrita
```
mkdir /var/backups/gestorlog
chmod -R 775 /var/backups/gestorlog
```
Criar uma pasta onde vai conter apenas os backup do dia com as devidas permissoes 
```
mkdir /var/backups/gestorlog/diario
chmod -R 775 /var/backups/gestorlog/diario
```
Compactar backups e formar um unico arquivo diario criar um script .sh em /var/backups/gestorlog e agendar no crontab 
```
nano /var/backups/gestorlog/backup_diario.sh
```
Adicionar o conteudo abaixo no arquivo criado backup_diario.sh
```
#!/bin/bash
ORIGEM_PASTA=/var/backups/gestorlog/diario
DATA=$(date +"%m-%d-%Y")
ARQUIVO_DESTINO="/var/backups/gestorlog/$DATA.tar.gz"
echo "Backup esta sendo gerando em /var/backups/gestorlog/$DATA.tar.gz aquivo, por favor aguarde..."
/bin/tar -czvf  $ARQUIVO_DESTINO $ORIGEM_PASTA
#apaga backup apos compactar
rm /var/backups/gestorlog/diario/*
#reinicia syslog
service syslog restart
```
Agendar no crontab
```
crontab -e
```
Adicione a linha abaixo caso queira que seja feito todo dia as 1 da manhã
```
0 1 * * * /bin/bash /var/backups/gestorlog/backup_diario.sh
```

Acesse a linha de comando e entre no servidor MySQL:
```
mysql
```
O Script vai retornar este resultado, que verifica que você está acessando um servidor MySQL.
```
mysql>
```
Então execute o seguinte comando:
```
CREATE USER 'novo_usuário'@'localhost' IDENTIFIED BY 'senha';
```
novo_usuário é o nome que damos para a nossa nova conta de usuário e a seção IDENTIFIED BY ‘senha’ define um código de acesso para esse usuário. Você pode substituir esses valores com os seus próprios, desde que só altere o que está dentro das aspas.
Para garantir todos os privilégios do banco de dados para um usuário recém-criado, execute o seguinte comando:
```
GRANT ALL PRIVILEGES ON * . * TO 'novo_usuario'@'localhost';
```
Para que as mudanças tenham efeito, execute imediatamente um flush dos privilégios ao executar o seguinte comando:
```
FLUSH PRIVILEGES;
```
