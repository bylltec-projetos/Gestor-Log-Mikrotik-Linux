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
//include("conexao.php");
require_once('../../Connections/site.php');
mysql_select_db($database_site, $site);
$sqlselecionausuariolog = "SELECT * FROM `usuario_log` ";
$queryselecionausuariolog = mysql_query($sqlselecionausuariolog) or die ("erro usuario do log");
// definir o numero de itens por pagina
$itens_por_pagina = 10000;

// pegar a pagina atual
$pagina_limite = intval($_GET['pagina_limite']);

$palavra = mysql_real_escape_string($_REQUEST['palavra']);
$ip = mysql_real_escape_string($_REQUEST['ipusuariolog']);
$data1 = mysql_real_escape_string($_REQUEST['data1']);
$data2 = mysql_real_escape_string($_REQUEST['data2']);
if($data1 != "" || $data2 != ""){ 
  $_SESSION['data1'] = $data1;
  $_SESSION['data2'] = $data2; 
}
  $data1 = $_SESSION['data1'];
  $data2 = $_SESSION['data2'];
//SELECT * FROM `SystemEvents` WHERE `Message` LIKE '%$palavra%' and `Message` LIKE '%$ip%' and `ReceivedAt`>='$data1 00:00:00' and `ReceivedAt`<='$data2 23:59:59' ORDER BY `ID` DESC
// puxar SystemEventss do banco

$sql_code = "SELECT * FROM `SystemEvents` WHERE `Message` LIKE '%$palavra%' and `Message` LIKE '%$ip%' and `ReceivedAt`>='$data1 00:00:00' and `ReceivedAt`<='$data2 23:59:59' ORDER BY `ID` DESC LIMIT $pagina_limite, $itens_por_pagina";
$execute = $mysqli->query($sql_code) or die($mysqli->error);
$SystemEvents = $execute->fetch_assoc();
$num = $execute->num_rows;
// pega a quantidade total de objetos no banco de dados
$num_total = $mysqli->query("select Message from SystemEvents")->num_rows;

// definir numero de páginas
$num_paginas = ceil($num_total/$itens_por_pagina);


?>
<head>
        <script src="exportar_excel.js"></script>
    </head>
    <h1>Exportar dados</h1>
<p>Clique no botÃ£o abaixo para gerar o aquivo .xls do Excel</p>

<input type="button" onclick="tableToExcel('testTable', 'W3C Example Table')" value="Exportar e faze download">

<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
                    
			 <span class="label label-default">Relatorio de acesso</span>
                         <form action="?pagina=relatorio" method="POST">

<table border="0" align="center">
    
    <tbody>
        <tr>
            <td>Usuario:</td>
            <td><select name="ipusuariolog" id="usuariolog">
              
              <?php 
               echo '<option value="">Todos</option>';
              while ($row_rsselecionausuariolog = mysql_fetch_assoc($queryselecionausuariolog)){
                    
                echo '<option value="'.$row_rsselecionausuariolog['ipusuariolog'].'">'.$row_rsselecionausuariolog['usuariolog'].'</option>';
                  
                  }
                  ?>
                
            
            
          </select></td>
            
            <td>De:</td>
            <td><input type="date" name="data1" value="<?php echo $data1;?>"></td>
            <td>AtÃ©:</td>
            <td><input type="date" name="data2" value="<?php echo $data2;?>"></td>
            <td><input type="text" name="palavra" value="" /></td>
            <td><input type="submit" value="buscar" /></td>
        </tr>
    </tbody>
</table>
</form>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Paginação</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>
  <body>

  	<div class="container-fluid">
  		<div class="row">
  			<div class="col-lg-12">
  				<h1>SystemEvents QTD <?php echo $num_total ?> encontrado</h1>
  				<?php if($num > 0){ ?>
				<table id="testTable" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Data/hora Recebida</th>
							<th>Data/hora Reportada</th>
							<th>Acesso</th>
							<th>Servidor</th>
						</tr>
					</thead>
					<tbody>
						<?php do{ ?>
						<tr>
							<td><?php echo $SystemEvents['ReceivedAt']; ?></td>
							<td><?php echo $SystemEvents['DeviceReportedTime']; ?></td>
							<td><?php echo $SystemEvents['Message']; ?></td>
							<td><?php echo $SystemEvents['FromHost']; ?></td>
						</tr>
						<?php } while($SystemEvents = $execute->fetch_assoc()); ?>
					</tbody>
				</table>

				<nav>
				  <ul class="pagination">
				    <li>
				      <a href="?pagina=relatorio&pagina_limite=0" aria-label="Previous">
				        <span aria-hidden="true">&laquo;</span>
				      </a>
				    </li>
				    <?php 
				    for($i=0;$i<$num_paginas;$i++){
				    $estilo = "";
				    if($pagina_limite == $i)
				    	$estilo = "class=\"active\"";
				    ?>
				    <li <?php echo $estilo; ?> ><a href="?pagina=relatorio&pagina_limite=<?php echo $i; ?>"><?php echo $i+1; ?></a></li>
					<?php } ?>
				    <li>
				      <a href="?pagina=relatorio&pagina_limite=<?php echo $num_paginas-1; ?>" aria-label="Next">
				        <span aria-hidden="true">&raquo;</span>
				      </a>
				    </li>
				  </ul>
				</nav>
  				<?php } ?>
  			</div>
  		</div>
  	</div>


  	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  	<!-- Include all compiled plugins (below), or include individual files as needed -->
  	<script src="js/bootstrap.min.js"></script>
  </body>
  </html>
