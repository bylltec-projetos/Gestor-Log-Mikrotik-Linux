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
  
    $sqlbuscausuario = "SELECT * FROM `usuario_log`";
    $querybuscausuario = $pdo->query($sqlbuscausuario);
    $totalusuariolog = $querybuscausuario->rowCount();

    $espaco_disco = disk_free_space(".");
    $si_prefix = array('B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB');
    $base = 1024;
    $class = min((int)log($espaco_disco, $base), count($si_prefix) - 1);
    $espaco_disco_formatado = sprintf('%1.2f', $espaco_disco / pow($base, $class)) . ' ' . $si_prefix[$class];

    $disco_total = disk_total_space("/");
    $class = min((int)log($disco_total, $base), count($si_prefix) - 1);
    $disco_total_formatado = sprintf('%1.2f', $disco_total / pow($base, $class)) . ' ' . $si_prefix[$class];
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}
?>

<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <span class="label label-default">Status do log</span>
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            Total de usuários
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Espaço Livre
                        </th>
                        <th>
                            Espaço Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php echo $totalusuariolog; ?>
                        </td>
                        <td>
                            Ativo
                        </td>
                        <td>
                            <?php echo $espaco_disco_formatado; ?>
                        </td>
                        <td>
                            <?php echo $disco_total_formatado; ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <?php
            require("backuplog/listar_arquivos.php");
            ?>
        </div>
    </div>
</div>





</body>
</html>


<html>
<head>

<style>
#pop{background-color:#b0c4de;display:none;position:absolute;top:50%;left:50%;margin-left:-150px;margin-top:-100px;padding:10px;width:auto;height:auto;border:1px solid #d0d0d0}

</style>
</head>
<body>
<div id="pop">
<a href="#" onClick="document.getElementById('pop').style.display='none';"></a>
<br />


		<p class="align-center"><img src="/site/images/progresso.gif" width="20" height="20" alt="progress"/>Aguarde... </p>
	<p>Processando Backup</p>

</div>


<html>
<meta http-equiv="refresh" content="120;URL=/site/gestorserver/log/?pagina=status">
</html>
