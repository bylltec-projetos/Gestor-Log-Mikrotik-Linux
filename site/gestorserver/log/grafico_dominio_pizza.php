<?php
// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION)) session_start();
$nivel_necessario = 5;
// Verifica se não há a variável da sessão que identifica o usuário
if (!isset($_SESSION['UsuarioID']) || ($_SESSION['UsuarioNivel'] > $nivel_necessario)) {
    // Destrói a sessão por segurança
    session_destroy();
    // Redireciona o visitante de volta para o login
    header("Location: /site/gestorserver/log/login.html");
    exit;
}
?>
<?php set_time_limit(0); ?>
<?php
require_once('../../Connections/site.php');
date_default_timezone_set('America/Campo_Grande');
$iduser = $_SESSION['iduser'];
$data1 = $_REQUEST['data1'];
$data2 = $_REQUEST['data2'];
$tipo = $_REQUEST['tipo'];
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

    $totalfiltro = "SELECT Message, Count(Message) AS ContMessage FROM SystemEvents WHERE `Message` LIKE :ip AND `SysLogTag`='web-proxy,account' and `ReceivedAt`>= :data1 AND `ReceivedAt`<= :data2 GROUP BY Message ORDER BY `ContMessage` DESC LIMIT 0,30;";
    $stmt_totalfiltro = $pdo->prepare($totalfiltro);
    $stmt_totalfiltro->bindValue(':ip', "%$ip%");
    $stmt_totalfiltro->bindValue(':data1', "$data1 00:00:00");
    $stmt_totalfiltro->bindValue(':data2', "$data2 23:59:59");
    $stmt_totalfiltro->execute();
    $resultotalfiltro = $stmt_totalfiltro->fetchAll(PDO::FETCH_ASSOC);

    $tudo2 = '';
    $array = array();

    foreach ($resultotalfiltro as $row_rslistafiltrografico2) {
        $nomefiltrocategoria = $row_rslistafiltrografico2['Message'];
        $posicao = strpos($nomefiltrocategoria, 'http://');
        if ($posicao !== false && $posicao > 0) {
            $texto = substr($nomefiltrocategoria, $posicao + 7);
            $string = explode("/", $texto);
            $nomefiltrocategoria = $string[0];
            $array[] = $nomefiltrocategoria;
        }
    }

    $array = array_unique($array);

    foreach ($array as $dominiobusca) {
        $queryfiltro = "SELECT COUNT(*) AS Total FROM SystemEvents WHERE `Message` LIKE :dominiobusca AND `Message` LIKE :ip AND `ReceivedAt`>= :data1 AND `ReceivedAt`<= :data2";
        $stmt_filtro = $pdo->prepare($queryfiltro);
        $stmt_filtro->bindValue(':dominiobusca', "%$dominiobusca%");
        $stmt_filtro->bindValue(':ip', "%$ip%");
        $stmt_filtro->bindValue(':data1', "$data1 00:00:00");
        $stmt_filtro->bindValue(':data2', "$data2 23:59:59");
        $stmt_filtro->execute();
        $row = $stmt_filtro->fetch(PDO::FETCH_ASSOC);

        $resultadototalfiltro = $row['Total'];

        if ($resultadototalfiltro <= 0) {
            $resultadototalfiltro = 0;
        } else {
            $tudo = "['$dominiobusca', $resultadototalfiltro],";
            $tudo2 .= $tudo;
        }
    }
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}
?>




<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>Grafico</title>

        <script type='text/javascript' src='/site/graficos/jqgrafico.js'></script>



        <link rel="stylesheet" type="text/css" href="/css/result-light.css">

        <style type='text/css'>

        </style>
    <form action="?pagina=estatistica" method="POST">

        <table border="0" align="center">

            <tbody>
                <tr>
                    <td>Usuario:</td>
                    <td><select name="ipusuariolog" id="usuariolog">

                            <?php
                            echo '<option value="">Todos</option>';
                            while ($row_rsselecionausuariolog = mysql_fetch_assoc($queryselecionausuariolog)) {

                                echo '<option value="' . $row_rsselecionausuariolog['ipusuariolog'] . '">' . $row_rsselecionausuariolog['usuariolog'] . '</option>';
                            }
                            ?>



                        </select></td>
                    <td>Tipo:</td>
                    <td><select name="tipografico" id="tipografico">

<?php
echo '<option value="pizza" selected>Pizza</option>';
echo '<option value="funil">Funil</option>';
?>



                        </select></td>
                    <td>De:</td>
                    <td><input type="date" name="data1" value="<?php echo $data1; ?>"></td>
                    <td>Até:</td>
                    <td><input type="date" name="data2" value="<?php echo $data2; ?>"></td>
                    <td><input type="submit" value="ok" onClick="document.getElementById('pop').style.display='block';" /></td>
                </tr>
            </tbody>
        </table>
    </form>

    <script type='text/javascript'>//<![CDATA[

        $(function () {
            $('#container').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: '<?php echo "Grafico de utilização por Dominio de $usuariolog IP: $ip " ?>'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
                    percentageDecimals: 1
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function () {
                                return '<b>' + this.point.name + '</b>: ' + this.percentage.toFixed(2) + ' %';
                            }
                        }
                    }
                },
                series: [{
                        type: 'pie',
                        name: 'Utilizando',
                        data: [
<?php
echo $tudo2;
?>




                        ]
                    }]
            });
        });


        //]]>

    </script>


</head>
<body>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>


</body>


</html>



<html>
<head>

<style>
#pop{background-color:#b0c4de;display:none;position:absolute;top:50%;left:50%;margin-left:-150px;margin-top:-100px;padding:10px;width:auto;height:auto;border:1px solid #d0d0d0}

</style>
</head>
<body>
<!--     <a onClick="document.getElementById('pop').style.display='block';" href="/site/gestorserver/log/?pagina=status">Processar Log</a>-->
<div id="pop">
<a href="#" onClick="document.getElementById('pop').style.display='none';"></a>
<br />


		<p class="align-center"><img src="/site/images/progresso.gif" width="20" height="20" alt="progress"/>Aguarde... </p>
	<p>Processando</p>




</div>
