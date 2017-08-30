<?php set_time_limit(0); ?>
<?php
require_once('../../Connections/site.php');
date_default_timezone_set('America/Campo_Grande');

//$iduser = $_SESSION['iduser'];
mysql_select_db($database_site, $site);
$palavra = mysql_real_escape_string($_REQUEST['palavra']);
$ip = mysql_real_escape_string($_REQUEST['ipusuariolog']);
$data1 = mysql_real_escape_string($_REQUEST['data1']);
$data2 = mysql_real_escape_string($_REQUEST['data2']);
$limite = mysql_real_escape_string($_REQUEST['limite']);
if ($limite == "") {
    $limite = "30";
}
//$data1br = date('d/m/Y', strtotime($data1));
//$data2br = date('d/m/Y', strtotime($data2));

if ($data1 == "") {
    $data1 = date('Y-m-d', strtotime("-7 days"));
}
if ($data2 == "") {
    $data2 = date('Y-m-d');
}

$sqlselecionausuariolog = "SELECT * FROM `usuario_log` ";
$queryselecionausuariolog = mysql_query($sqlselecionausuariolog) or die("erro ao localizar usuario do log");
?>

<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">

            <span class="label label-default">Rank de sites mais visitados</span>
            <form action="?pagina=rank" method="POST">

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

                            <td>De:</td>
                            <td><input type="date" name="data1" value="<?php echo $data1; ?>"></td>
                            <td>Até:</td>
                            <td><input type="date" name="data2" value="<?php echo $data2; ?>"></td>
                            <td>Top: <input type="text" name="limite" value="<?php echo $limite; ?>" /></td>
                            <td><input type="submit" value="buscar" /></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <table class="table">


                <thead>

                    <tr>
                        <th>
                            Dominio/host/site
                        </th>

                        <th>
                            QTD
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
//SELECT dominio, Count(dominio) AS ContDominio FROM log WHERE `data`>='2014-09-12' and `data`<='2014-09-13' GROUP BY  Dominio ORDER BY `ContDominio` DESC LIMIT 0,30
//$sqlbuscalog = "SELECT dominio, Count(dominio) AS ContDominio FROM log WHERE `linhalog` LIKE '%$ip%' and `data`>='$data1' and `data`<='$data2' GROUP BY  Dominio ORDER BY `ContDominio` DESC LIMIT 0,$limite  ";
                    $sqlbuscalog = "SELECT Message, Count(Message) AS ContMessage FROM SystemEvents WHERE `Message` LIKE '%$ip%' and `SysLogTag`='web-proxy,account' and `ReceivedAt`>='$data1 00:00:00' and `ReceivedAt`<='$data2 23:59:59' GROUP BY  Message ORDER BY `ContMessage` DESC LIMIT 0,$limite;";

                    $querybuscalog = mysql_query($sqlbuscalog) or die("erro ao buscar log");

                    while ($row_rsbuscalog = mysql_fetch_assoc($querybuscalog)) {
                        $dominio = $row_rsbuscalog['Message'];
                        $posicao = strpos($dominio, 'http://');
                        if ($posicao != "" || $posicao > 0) {
                            $texto = substr($dominio, $posicao + 7); // pablo.blog.br


                            $string = explode("/", $texto);

                            $dominio = $string[0];
//$dominio= $texto;
//$array[] = $dominio;
                            ?>
                            <tr>
                                <td>
                                    <?php echo $dominio ?>
                                </td>

                                <td>
                                    <?php echo $row_rsbuscalog['ContMessage'] ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>




                </tbody>
            </table>

        </div>
    </div>
</div>

