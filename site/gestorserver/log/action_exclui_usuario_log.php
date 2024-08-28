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

    $idusuariolog = $_REQUEST["id"];

    if (empty($idusuariolog)) {
        echo "<script type='text/javascript'> alert('Escolha o usuário que deseja excluir.');</script>";
        echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';
    } else {
        $sqldeletausuario = "DELETE FROM `$database_site`.`usuario_log` WHERE `idusuariolog` = :idusuariolog";
        $stmt = $pdo->prepare($sqldeletausuario);
        $stmt->bindValue(':idusuariolog', $idusuariolog);
        $stmt->execute();

        echo "<script type='text/javascript'> alert('Usuário excluído com sucesso.');</script>";
        echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=adicionar_usuario"></head>';
    }
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}
?>
