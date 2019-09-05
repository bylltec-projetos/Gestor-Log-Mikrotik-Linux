<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_site = "localhost";
$database_site = "Syslog";
$username_site = "root";
$password_site = "123456";
$site = mysql_pconnect($hostname_site, $username_site, $password_site) or trigger_error(mysql_error(),E_USER_ERROR); 
$mysqli = new mysqli($hostname_site, $username_site, $password_site, $database_site);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} 

?>
