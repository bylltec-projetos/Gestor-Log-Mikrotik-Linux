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
require_once('../../Connections/site.php');
$iduser = $_SESSION['iduser'];
$data1 = $_REQUEST['data1'];
$data2 = $_REQUEST['data2'];
$tipo = $_REQUEST['tipo'];
$conta = $_REQUEST['conta'];
$data1br = date('d/m/Y', strtotime($data1));
$data2br = date('d/m/Y', strtotime($data2));

try {
    $totalfiltro = "SELECT DISTINCT dominio FROM log WHERE `iduser` = :iduser";
    $stmt_totalfiltro = $pdo->prepare($totalfiltro);
    $stmt_totalfiltro->bindValue(':iduser', $iduser);
    $stmt_totalfiltro->execute();
    $resultotalfiltro = $stmt_totalfiltro->fetchAll(PDO::FETCH_ASSOC);

    $tudo2 = '';
    $categoriainteira = '';
    $totaldominiolistado = '';

    foreach ($resultotalfiltro as $row_rslistafiltrografico2) {
        $nomefiltrocategoria = $row_rslistafiltrografico2['dominio'];

        $queryfiltro = "SELECT * FROM log WHERE `linhalog` LIKE :nomefiltrocategoria AND `iduser` = :iduser";
        $stmt_filtro = $pdo->prepare($queryfiltro);
        $stmt_filtro->bindValue(':nomefiltrocategoria', "%$nomefiltrocategoria%");
        $stmt_filtro->bindValue(':iduser', $iduser);
        $stmt_filtro->execute();
        $resultfiltro = $stmt_filtro->fetchAll(PDO::FETCH_ASSOC);

        $resultadototalfiltro = count($resultfiltro);

        if ($resultadototalfiltro <= 0) {
            $resultadototalfiltro = 0;
        } else {
            $tudo = "['$nomefiltrocategoria', $resultadototalfiltro],";
            $tudo2 .= $tudo;
            $categoria = "'$nomefiltrocategoria',";
            $categoriainteira .= $categoria;
            $totaldominio = "'$resultadototalfiltro',";
            $totaldominiolistado .= $totaldominio;
        }
    }
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            chart: {
                type: 'column',
                margin: [ 50, 50, 100, 80]
            },
            title: {
                text: 'World\'s largest cities per 2008'
            },
            xAxis: {
                categories: [
                    <?php
                    echo $categoriainteira;
                    ?>
                ],
                labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Population (millions)'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>',
            },
            series: [{
                name: 'Population',
                data: [
            <?php
            echo $totaldominiolistado;
            ?>
                ],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: 4,
                    y: 10,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 3px black'
                    }
                }
            }]
        });
    });


		</script>
	</head>
	<body>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/funnel.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 410px; max-width: 600px; height: 400px; margin: 0 auto"></div>
	</body>
</html>
