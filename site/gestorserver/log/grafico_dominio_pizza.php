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
    header("Location: /site/gestorserver/log/login.html");
    exit;
}
?>
<?php set_time_limit(0); ?>
<?php
require_once('../../Connections/site.php');
date_default_timezone_set('America/Campo_Grande');
$iduser = $_SESSION['iduser'];
$data1 = mysql_real_escape_string($_REQUEST['data1']);
$data2 = mysql_real_escape_string($_REQUEST['data2']);
$tipo = mysql_real_escape_string($_REQUEST['tipo']);
$ip = mysql_real_escape_string($_REQUEST['ipusuariolog']);
//$data1br = date('d/m/Y', strtotime($data1));
//$data2br = date('d/m/Y', strtotime($data2));
//
//if ( $data1 == "") {
//    $data1 = date('Y-m-d', strtotime("-1 days"));  
//}
//if ( $data2 == "") {    
//   $data2 = date('Y-m-d');   
//}

mysql_select_db($database_site, $site);

$sqlselecionausuariolog = "SELECT * FROM `usuario_log` ";
$queryselecionausuariolog = mysql_query($sqlselecionausuariolog) or die("sql select erro");

if ($ip != "") {

    $sqlselecionausuariolog2 = "SELECT * FROM `usuario_log` where `ipusuariolog`= '$ip' ";
    $queryselecionausuariolog2 = mysql_query($sqlselecionausuariolog2) or die("sql select erro2");
    $row_rsselecionausuariolog2 = mysql_fetch_assoc($queryselecionausuariolog2);
    $usuariolog = $row_rsselecionausuariolog2['usuariolog'];
}

//SELECT dominio, Count(dominio) AS ContDominio FROM log WHERE `data`>='2014-09-12' and `data`<='2014-09-13' GROUP BY  Dominio ORDER BY `ContDominio` DESC LIMIT 0,30
$totalfiltro = "SELECT Message, Count(Message) AS ContMessage FROM SystemEvents WHERE `Message` LIKE '%$ip%' and `SysLogTag`='web-proxy,account' and `ReceivedAt`>='$data1 00:00:00' and `ReceivedAt`<='$data2 23:59:59' GROUP BY  Message ORDER BY `ContMessage` DESC LIMIT 0,30;";
$resultotalfiltro = mysql_query($totalfiltro) or die(mysql_error());
//echo $totalfiltro;
//echo "<br>";
while ($row_rslistafiltrografico2 = mysql_fetch_assoc($resultotalfiltro)) {

    $nomefiltrocategoria = $row_rslistafiltrografico2['Message'];
    $posicao = strpos($nomefiltrocategoria, 'http://');
if($posicao!="" || $posicao > 0){
//    echo $posicao;
//    echo "<br>";
$texto= substr($nomefiltrocategoria, $posicao+7); // pablo.blog.br
$string=explode("/", $texto);
$nomefiltrocategoria= $string[0];
$array[] = $nomefiltrocategoria;
}    
}


$array = array_unique($array);
//print_r($array);
//echo "<br>";

foreach ($array as $dominiobusca) {
    
    
   $queryfiltro = "SELECT COUNT(*) Total FROM SystemEvents WHERE `Message` LIKE '%$dominiobusca%' and `Message` LIKE '%$ip%'and `ReceivedAt`>='$data1 00:00:00' and `ReceivedAt`<='$data2 23:59:59' ";
   //echo $queryfiltro; 
   
   $resultfiltro = mysql_query($queryfiltro) or die(mysql_error());
    //$row = mysql_fetch_array($resultfiltro);
    $row = mysql_fetch_assoc($resultfiltro);

    //$resultadototalfiltro = mysql_num_rows($resultfiltro);
    $resultadototalfiltro = $row['Total'];


    if ($resultadototalfiltro <= 0) {

        $resultadototalfiltro = 0;
    } else {


        $tudo = "['$dominiobusca',       $resultadototalfiltro],";

        $tudo2 = $tudo2 . $tudo;
    }
    
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