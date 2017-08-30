<?php
// A sess�o precisa ser iniciada em cada p�gina diferente
if (!isset($_SESSION)) session_start();
$nivel_necessario = 5;
// Verifica se n�o h� a vari�vel da sess�o que identifica o usu�rio
if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel'] > $nivel_necessario)) {
	// Destr�i a sess�o por seguran�a
	session_destroy();
	// Redireciona o visitante de volta pro login
	header("Location: /site/login/index.php"); exit;
}
?>

<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../../Connections/site.php');
$iduser = $_SESSION['iduser'];
$data1 = mysql_real_escape_string($_REQUEST['data1']);
$data2 = mysql_real_escape_string($_REQUEST['data2']);
$tipo = mysql_real_escape_string($_REQUEST['tipo']);
$conta = mysql_real_escape_string($_REQUEST['conta']);
$data1br = date('d/m/Y', strtotime($data1));
$data2br = date('d/m/Y', strtotime($data2));
//echo '<tr><td>Conta: '.$conta.'</td>  <td>'$tipo'</td>  <td>'$data1''$data2'</td> </tr>';
//echo $iduser;
//seleciona banco de dados
mysql_select_db($database_site, $site);

$totalfiltro = "SELECT distinct dominio FROM log WHERE `iduser` = $iduser ;"; 
                     $resultotalfiltro = mysql_query($totalfiltro) or die(mysql_error());
                                  
    while ($row_rslistafiltrografico2 = mysql_fetch_assoc($resultotalfiltro)){
                     
                $nomefiltrocategoria = $row_rslistafiltrografico2['dominio'];     
                     
                   $queryfiltro = "SELECT * FROM log WHERE `linhalog` LIKE '%$nomefiltrocategoria%' AND `iduser` = '$iduser'  ";  
 $resultfiltro = mysql_query($queryfiltro) or die(mysql_error());
$row = mysql_fetch_array($resultfiltro);


$resultadototalfiltro = mysql_num_rows($resultfiltro);


if ($resultadototalfiltro <= 0){
    
 $resultadototalfiltro = 0;   
}
 else {
    

$tudo =  "['$nomefiltrocategoria',       $resultadototalfiltro],";
  //echo "['$nomefiltrocategoria',       $resultadototalfiltro],";  
   $tudo2 = $tudo2.$tudo;
   $categoria =  "'$nomefiltrocategoria',";
   $categoriainteira = $categoriainteira.$categoria;
   $totaldominio =  "'$resultadototalfiltro',";
   $totaldominiolistado = $totaldominiolistado.$totaldominio;
   }
                 } 
                 //echo $tudo2;
                 
                 //echo $totaldominiolistado;
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