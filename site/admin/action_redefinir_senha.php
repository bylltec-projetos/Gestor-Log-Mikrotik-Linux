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
require_once('../Connections/site.php');

//recebe os dados
$nsenha = $_POST["nsenha"];
$rsenha = $_POST["rsenha"];
$iduser = $_SESSION['iduser'];
$demo = 0;

//validação de dados
if ($nsenha == "") {
    echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=alterar_senha"></head>';
    echo "<script type='text/javascript'> alert('É necessário uma senha');</script>";
} elseif ($nsenha != $rsenha) {
    echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=alterar_senha"></head>';
    echo "<script type='text/javascript'> alert('Repita a senha corretamente.');</script>";
} elseif (strlen($nsenha) < 5) {
    echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=alterar_senha"></head>';
    echo "<script type='text/javascript'> alert('A senha deve conter pelo menos 5 caracteres');</script>";
} else {
    if ($demo) {
        echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>';
        echo "<script type='text/javascript'> alert('Demo não pode mudar a senha, adquira uma licença.');</script>";
    } else {
        $novasenharedefinida = sha1($nsenha);
        $sqlredefinirsenha = "UPDATE `$database_site`.`usuarios` SET `senha` = :novasenharedefinida WHERE `usuarios`.`iduser` = :iduser";

        $stmt = $pdo->prepare($sqlredefinirsenha);
        $stmt->bindParam(':novasenharedefinida', $novasenharedefinida);
        $stmt->bindParam(':iduser', $iduser);
        $stmt->execute();

        echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>';
        echo "<script type='text/javascript'> alert('Senha alterada com sucesso.');</script>";
    }
}
?>
