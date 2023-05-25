<?php
// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION))
    session_start();
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
<?php set_time_limit(0); ?>
<?php
require_once('../../Connections/site.php');
$iduser = $_SESSION['iduser'];
$data1 = $_REQUEST['data1'];
$data2 = $_REQUEST['data2'];
$tipo = $_REQUEST['tipo'];
$conta = $_REQUEST['conta'];
$ip = $_REQUEST['ipusuariolog'];

try {

    $sqlselecionausuariolog = "SELECT * FROM `usuario_log` ";
    $stmt_selecionausuariolog = $pdo->query($sqlselecionausuariolog);
    $row_rsselecionausuariolog = $stmt_selecionausuariolog->fetchAll(PDO::FETCH_ASSOC);

    if ($ip != "") {
        $sqlselecionausuariolog2 = "SELECT * FROM `usuario_log` where `ipusuariolog`= :ip";
        $stmt_selecionausuariolog2 = $pdo->prepare($sqlselecionausuariolog2);
        $stmt_selecionausuariolog2->bindValue(':ip', $ip);
        $stmt_selecionausuariolog2->execute();
        $row_rsselecionausuariolog2 = $stmt_selecionausuariolog2->fetch(PDO::FETCH_ASSOC);
        $usuariolog = $row_rsselecionausuariolog2['usuariolog'];
    }

    $queryfiltro = "SELECT Message FROM SystemEvents WHERE `Message` LIKE :ip AND `SysLogTag` LIKE 'web-proxy,account' AND `ReceivedAt` >= :data1 AND `ReceivedAt` <= :data2 GROUP BY Message ORDER BY `Message`";
    $stmt_filtro = $pdo->prepare($queryfiltro);
    $stmt_filtro->bindValue(':ip', "%$ip%");
    $stmt_filtro->bindValue(':data1', "$data1 00:00:00");
    $stmt_filtro->bindValue(':data2', "$data2 23:59:59");
    $stmt_filtro->execute();
    $resultfiltro = $stmt_filtro->fetchAll(PDO::FETCH_ASSOC);

    $tudo2 = '';
    $acessos = array();

    foreach ($resultfiltro as $row_rslistafiltrografico2) {
        $dominio = $row_rslistafiltrografico2['Message'];
        $posicao = strpos($dominio, 'http://');
        if ($posicao !== false && $posicao > 0) {
            $texto = substr($dominio, $posicao + 7);
            $string = explode("/", $texto);
            $nomefiltrocategoria = $string[0];
            $acessos[] = $nomefiltrocategoria;
        }
        $posicao = strpos($nomefiltrocategoria, 'https://');
        if ($posicao !== false && $posicao > 0) {
            $texto = substr($nomefiltrocategoria, $posicao + 8);
            $string = explode("/", $texto);
            $nomefiltrocategoria = $string[0];
            $array[] = $nomefiltrocategoria;
        }
    }

    $acessos_cont = array_count_values($acessos);
    arsort($acessos_cont);

    foreach (array_slice($acessos_cont, 0, 30) as $chave => $conteudo) {
        $tudo = "['$chave',$conteudo],";
        $tudo2 .= $tudo;
    }
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}
?>

<form action="?pagina=estatistica" method="POST">
    <table border="0" align="center">
        <tbody>
            <tr>
                <td>Usuario:</td>
                <td>
                    <select name="ipusuariolog" id="usuariolog">
                        <option value="">Todos</option>
                        <?php
                        foreach ($row_rsselecionausuariolog as $row) {
                            echo '<option value="' . $row['ipusuariolog'] . '">' . $row['usuariolog'] . '</option>';
                        }
                        ?>
                    </select>
                </td>
                <td>Tipo:</td>
                <td>
                    <select name="tipografico" id="tipografico">
                        <option value="pizza">Pizza</option>
                        <option value="funil" selected>Funil</option>
                    </select>
                </td>
                <td>De:</td>
                <td><input type="date" name="data1" value="<?php echo $data1; ?>"></td>
                <td>Até:</td>
                <td><input type="date" name="data2" value="<?php echo $data2; ?>"></td>
                <td><input type="submit" value="ok" onClick="document.getElementById('pop').style.display = 'block';"></td>
            </tr>
        </tbody>
    </table>
</form>


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/funnel.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 410px; max-width: 600px; height: 400px; margin: 0 auto"></div>

<script type="text/javascript">
Highcharts.chart('container', {
    chart: {
        type: 'funnel'
    },
    title: {
        text: '<?php echo "Grafico de utilização por Dominio de $usuariolog IP: $ip " ?>'
    },
    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b> ({point.y:,.0f})',
                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
                softConnector: true
            },
            center: ['40%', '50%'],
            neckWidth: '20%',
            neckHeight: '0%',
            width: '50%'
        }
    },
    legend: {
        enabled: false
    },
    series: [{
        name: 'Visitas',
        data: [
            <?php
                echo $tudo2;
            ?>
        ]
    }]
});

</script>




<html>
    <head>

        <style>
            #pop{background-color:#b0c4de;display:none;position:absolute;top:50%;left:50%;margin-left:-150px;margin-top:-100px;padding:10px;width:auto;height:auto;border:1px solid #d0d0d0}

        </style>
    </head>
    <body>
        <!--     <a onClick="document.getElementById('pop').style.display='block';" href="/site/gestorserver/log/?pagina=status">Processar Log</a>-->
        <div id="pop">
            <a href="#" onClick="document.getElementById('pop').style.display = 'none';"></a>
            <br />


            <p class="align-center"><img src="/site/images/progresso.gif" width="20" height="20" alt="progress"/>Aguarde... </p>
            <p>Processando</p>




        </div>
