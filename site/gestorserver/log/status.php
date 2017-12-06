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
require '../../Connections/site.php';
mysql_select_db($database_site, $site);





$sqlbuscausuario = "SELECT * FROM `usuario_log` ";

$querybuscausuario = mysql_query($sqlbuscausuario) or die ("sql filtro grupo erro");
$totalusuariolog = mysql_num_rows($querybuscausuario);

$sqlbuscalog = "SELECT COUNT(*) as qtdlog FROM `SystemEvents` ";

$querybuscalog = mysql_query($sqlbuscalog) or die ("sql filtro grupo erro");

$totallog = mysql_fetch_assoc($querybuscalog)

 

?>


<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			 <span class="label label-default">Status do log</span>
			<table class="table">
				<thead>
					<tr>
						<th>
							Total de log
						</th>
						<th>
							Total de usuarios
						</th>
						<th>
							status
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
							<?php echo $totallog[qtdlog];?>
						</td>
						<td>
							<?php echo $totalusuariolog ; ?>
						</td>
						<td>
							Ativo
						</td>
						<td>
						<?php 

						$espaco_disco = disk_free_space("."); 
					    $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
					    $base = 1024;
					    $class = min((int)log($espaco_disco , $base) , count($si_prefix) - 1);
					    //echo $bytes . '<br />';
					    echo sprintf('%1.2f' , $espaco_disco / pow($base,$class)) . ' ' . $si_prefix[$class] . '<br />';
						?>	
						</td>
						<td>
							<?php 
							$disco_total = disk_total_space("/");
							$si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
					    	$base = 1024;
					    	$class = min((int)log($disco_total , $base) , count($si_prefix) - 1);
					    	//echo $bytes . '<br />';
					    	echo sprintf('%1.2f' , $disco_total / pow($base,$class)) . ' ' . $si_prefix[$class] . '<br />';
							/*
							function getSymbolByQuantity($bytes) {
								    $symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
								    $exp = floor(log($bytes)/log(1024));

								    return sprintf('%.2f '.$symbol[$exp], ($bytes/pow(1024, floor($exp))));
								}
							echo getSymbolByQuantity($disco_total);	
							*/
							?>
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