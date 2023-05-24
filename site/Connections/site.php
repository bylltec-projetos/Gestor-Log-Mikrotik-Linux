<?php
$hostname_site = "localhost";
$database_site = "Syslog";
$username_site = "gestorlog";
$password_site = "@#gestorlog";

try {
    $pdo = new PDO("mysql:host=$hostname_site;dbname=$database_site", $username_site, $password_site);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}
?>
