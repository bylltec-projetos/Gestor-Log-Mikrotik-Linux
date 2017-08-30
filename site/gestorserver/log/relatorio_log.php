<?php
require_once('../../Connections/site.php');

//$iduser = $_SESSION['iduser'];
mysql_select_db($database_site, $site);
$palavra = mysql_real_escape_string($_REQUEST['palavra']);
$ip = mysql_real_escape_string($_REQUEST['ipusuariolog']);
$data1 = mysql_real_escape_string($_REQUEST['data1']);
$data2 = mysql_real_escape_string($_REQUEST['data2']);
//$data1br = date('d/m/Y', strtotime($data1));
//$data2br = date('d/m/Y', strtotime($data2));

//if ( $data1 == "") {
//   $data1 = date('Y-m-d', strtotime("-7 days")); 
//   
//  
//}
//if ( $data2 == "") {
//    
//   $data2 = date('Y-m-d'); 
//  
//}


$sqlbuscalog = "SELECT * FROM `SystemEvents` WHERE `Message` LIKE '%$palavra%' and `Message` LIKE '%$ip%' and `ReceivedAt`>='$data1 00:00:00' and `ReceivedAt`<='$data2 23:59:59' ORDER BY `ID` DESC";
//echo $sqlbuscalog;
$querybuscalog = mysql_query($sqlbuscalog) or die ("erro ao buscar log");
   
$sqlselecionausuariolog = "SELECT * FROM `usuario_log` ";
$queryselecionausuariolog = mysql_query($sqlselecionausuariolog) or die ("erro ao localizar usuario do log");
                
?>
<head>
        <script src="exportar_excel.js"></script>
    </head>
    <h1>Exportar dados</h1>
<p>Clique no botão abaixo para gerar o aquivo .xls do Excel</p>

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
            <td>Até:</td>
            <td><input type="date" name="data2" value="<?php echo $data2;?>"></td>
            <td><input type="text" name="palavra" value="" /></td>
            <td><input type="submit" value="buscar" /></td>
        </tr>
    </tbody>
</table>
</form>

			<table class="table" id="testTable" >
				
                            
                            <thead>
                                    
					<tr>
						<th>
							Data/hora Recebida
						</th>
						<th>
							Data/hora Reportada
						</th>
						<th>
							Acesso
						</th>
						<th>
							Servidor
						</th>
					</tr>
				</thead>
				<tbody>
                                    <?php
       while ($row_rsbuscalog = mysql_fetch_assoc($querybuscalog)){
           
?>
					<tr>
						<td>
							<?php echo  $row_rsbuscalog['ReceivedAt']?>
						</td>
						<td>
							<?php echo  $row_rsbuscalog['DeviceReportedTime']?>
						</td>
						<td>
							<?php echo  $row_rsbuscalog['Message']?>
						</td>
						<td>
							<?php echo  $row_rsbuscalog['FromHost']?>
						</td>
					</tr>
                                         <?php     
                    
                    }
        ?>
					
					
					
					
				</tbody>
			</table>
<!--			<ul class="pagination">
				<li>
					<a href="#">Anterior</a>
				</li>
				<li>
					<a href="#">1</a>
				</li>
				<li>
					<a href="#">2</a>
				</li>
				<li>
					<a href="#">3</a>
				</li>
				<li>
					<a href="#">4</a>
				</li>
				<li>
					<a href="#">5</a>
				</li>
				<li>
					<a href="#">Proximo</a>
				</li>
			</ul>-->
		</div>
	</div>
</div>

