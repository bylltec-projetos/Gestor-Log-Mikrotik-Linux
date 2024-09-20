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
require_once '../../../Connections/site.php';

$sql_limpa_tabela = "TRUNCATE TABLE SystemEvents";

$stmt = $pdo->prepare($sql_limpa_tabela);
$stmt->execute();

echo "<script type='text/javascript'> alert('Banco de dados de Log foi limpo e liberado espaço de armazenamento.');</script>";
echo '<head><meta http-equiv=refresh content="0; URL=/site/gestorserver/log/?pagina=status"></head>';
?>
