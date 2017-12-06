<?php

// A sess�o precisa ser iniciada em cada p�gina diferente
if (!isset($_SESSION))
    session_start();
$nivel_necessario = 5;
// Verifica se n�o h� a vari�vel da sess�o que identifica o usu�rio
if (!isset($_SESSION['UsuarioID']) OR ( $_SESSION['UsuarioNivel'] > $nivel_necessario)) {
    // Destr�i a sess�o por seguran�a
    session_destroy();
    // Redireciona o visitante de volta pro login
    header("Location: /site/login/index.php");
    exit;
}
?>
<?php

require_once('../Connections/site.php');
//recebe os dados 
$nsenha = mysql_real_escape_string($_POST["nsenha"]);
$rsenha = mysql_real_escape_string($_POST["rsenha"]);
//$rsenha = sha1($_POST["rsenha"]);
$iduser = $_SESSION['iduser'];
//demo nao pode mudar a senha 1 para demo e 0 para sistema valido
$demo = 0;
//valida��o de dados
if ($nsenha == "") {
    echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=alterar_senha"></head>';
    echo "<script type='text/javascript'> alert('É necessario uma senha');</script>";
} elseif ($nsenha != $rsenha) {
    echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=alterar_senha"></head>';
    echo "<script type='text/javascript'> alert('Repita a senha corretamente.');</script>";
} elseif (strlen($nsenha) < 5) {
    echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=alterar_senha"></head>';
    echo "<script type='text/javascript'> alert('A senha deve conter pelomenos 5 caracteres');</script>";
} else {
    if ($demo) {
        // Redireciona o visitante
        echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>';
        // Mensagem de erro quando os dados s�o inv�lidos e/ou o usu�rio n�o foi encontrado
        echo "<script type='text/javascript'> alert('Demo nao pode ser mudado a senha, adquira uma licença.');</script>";
    } else {


//$iduser = $_SESSION['iduser'];
//echo $iduser;
//seleciona banco de dados
        mysql_select_db($database_site, $site);
        $novasenharedefinida = sha1($nsenha);
        $sqlredefinirsenha = "UPDATE `$database_site`.`usuarios` SET `senha` = '$novasenharedefinida' WHERE `usuarios`.`iduser` = '$iduser';";
//verifica se foi inserido corretamente os dados na tabela
        mysql_query($sqlredefinirsenha) or die("nao foi possivel redefinir a nova senha");

        // Redireciona o visitante
        echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>';
        // Mensagem de erro quando os dados s�o inv�lidos e/ou o usu�rio n�o foi encontrado
        echo "<script type='text/javascript'> alert('Senha alterada com sucesso.');</script>";
    }
}
?>

