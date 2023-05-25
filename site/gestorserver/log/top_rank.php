<?php
set_time_limit(0);
require_once('../../Connections/site.php');
date_default_timezone_set('America/Campo_Grande');

//$iduser = $_SESSION['iduser'];

$palavra = $_REQUEST['palavra'];
$ip = $_REQUEST['ipusuariolog'];
$data1 = $_REQUEST['data1'];
$data2 = $_REQUEST['data2'];
$limite = $_REQUEST['limite'];
if ($limite == "") {
    $limite = "30";
}

if ($data1 == "") {
    $data1 = date('Y-m-d', strtotime("-7 days"));
}
if ($data2 == "") {
    $data2 = date('Y-m-d');
}

$sqlselecionausuariolog = "SELECT * FROM `usuario_log` ";
$queryselecionausuariolog = $pdo->query($sqlselecionausuariolog);

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
                            <td>
                                <select name="ipusuariolog" id="usuariolog">
                                    <?php
                                    echo '<option value="">Todos</option>';
                                    while ($row_rsselecionausuariolog = $queryselecionausuariolog->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row_rsselecionausuariolog['ipusuariolog'] . '">' . $row_rsselecionausuariolog['usuariolog'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
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
                        <th>Dominio/host/site</th>
                        <th>QTD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sqlbuscalog = "SELECT Message, Count(Message) AS ContMessage FROM SystemEvents WHERE `Message` LIKE :ip AND `SysLogTag`='web-proxy,account' AND `ReceivedAt`>=:data1 AND `ReceivedAt`<=:data2 GROUP BY Message ORDER BY ContMessage DESC LIMIT 0,:limite;";
                    $querybuscalog = $pdo->prepare($sqlbuscalog);
                    $querybuscalog->bindValue(':ip', "%$ip%");
                    $querybuscalog->bindValue(':data1', "$data1 00:00:00");
                    $querybuscalog->bindValue(':data2', "$data2 23:59:59");
                    $querybuscalog->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
                    $querybuscalog->execute();

                    while ($row_rsbuscalog = $querybuscalog->fetch(PDO::FETCH_ASSOC)) {
                        $dominio = $row_rsbuscalog['Message'];
                        $posicao = strpos($dominio, 'http://');
                        if ($posicao !== false && $posicao >= 0) {
                            $texto = substr($dominio, $posicao + 7);
                            $string = explode("/", $texto);
                            $dominio = $string[0];
                            ?>
                            <tr>
                                <td><?php echo $dominio ?></td>
                                <td><?php echo $row_rsbuscalog['ContMessage'] ?></td>
                            </tr>
                            <?php
                        }
                        $posicao = strpos($dominio, 'https://');
                        if ($posicao !== false && $posicao >= 0) {
                            $texto = substr($dominio, $posicao + 8);
                            $string = explode("/", $texto);
                            $dominio = $string[0];
                            ?>
                            <tr>
                                <td><?php echo $dominio ?></td>
                                <td><?php echo $row_rsbuscalog['ContMessage'] ?></td>
                            </tr>
                            <?php
                        }
                        $posicao = strpos($dominio, 'CONNECT');
                        if ($posicao !== false && $posicao >= 0) {
                            $texto = substr($dominio, $posicao + 8);
                            $string = explode(" ", $texto);
                            $dominio = $string[0];
                            ?>
                            <tr>
                                <td><?php echo $dominio ?></td>
                                <td><?php echo $row_rsbuscalog['ContMessage'] ?></td>
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
