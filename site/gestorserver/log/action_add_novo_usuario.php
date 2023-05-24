<?php
// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION)) session_start();
$nivel_necessario = 5;
// Verifica se não há a variável da sessão que identifica o usuário
if (!isset($_SESSION['UsuarioID']) || ($_SESSION['UsuarioNivel'] > $nivel_necessario)) {
    // Destrói a sessão por segurança
    session_destroy();
    // Redireciona o visitante de volta para o login
    header("Location: /site/login/index.php");
    exit;
}
?>

<?php
require '../../Connections/site.php';

try {
  
    $nomeusuario = $_REQUEST["nomeusuario"];
    $ipusuario = $_REQUEST["ipusuario"];
    $obs = $_REQUEST["obs"];

    $sqlbuscausuario = "SELECT * FROM `usuario_log` WHERE `usuariolog` LIKE :nomeusuario";
    $stmt = $pdo->prepare($sqlbuscausuario);
    $stmt->bindValue(':nomeusuario', $nomeusuario);
    $stmt->execute();
    $totalusuariolog = $stmt->rowCount();

    if (empty($nomeusuario)) {
        echo "<script type='text/javascript'> alert('Preencha o nome.');</script>";
        echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';
    } elseif (empty($ipusuario)) {
        echo "<script type='text/javascript'> alert('Preencha o IP.');</script>";
        echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';
    } elseif ($totalusuariolog > 0) {
        echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';
        echo "<script type='text/javascript'> alert('Este usuário já existe.');</script>";
    } else {
        $sqladicionafiltrogrupo = "INSERT INTO `$database_site`.`usuario_log` (`idusuariolog`,`usuariolog`,`ipusuariolog`,`obs`) VALUES (NULL, :nomeusuario, :ipusuario, :obs)";
        $stmt = $pdo->prepare($sqladicionafiltrogrupo);
        $stmt->bindValue(':nomeusuario', $nomeusuario);
        $stmt->bindValue(':ipusuario', $ipusuario);
        $stmt->bindValue(':obs', $obs);
        $stmt->execute();

        echo "<script type='text/javascript'> alert('O usuário $nomeusuario foi adicionado com sucesso.');</script>";
        echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';
    }
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}
?>
