<?php
require_once('../../Connections/site.php');

//$iduser = $_SESSION['iduser'];
mysql_select_db($database_site, $site);

  
$sqlselecionausuariolog = "SELECT * FROM `usuario_log` ORDER BY  `usuariolog` ASC ";
$queryselecionausuariolog = mysql_query($sqlselecionausuariolog) or die ("erro ao localizar usuario do log");
                
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Usuarios</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

	<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
	<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
	<!--script src="js/less-1.3.3.min.js"></script-->
	<!--append ‘#!watch’ to the browser URL, then refresh the page. -->
	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

  <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
  <![endif]-->

  <!-- Fav and touch icons -->
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="img/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="img/apple-touch-icon-57-precomposed.png">
  <link rel="shortcut icon" href="img/favicon.png">
  
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
</head>

<body>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
                    
			 <span class="label label-default">Usuarios</span>
                        
			<table class="table">
				
                            
                            <thead>
                                    
					<tr>
						<th>
							Usuario
						</th>
						<th>
							IP
						</th>
						<th>
							OBS
						</th>
						<th>
							Excluir
						</th>
					</tr>
				</thead>
				<tbody>
                                    <?php
       while ($row_rsselecionausuariolog = mysql_fetch_assoc($queryselecionausuariolog)){
           
?>
					<tr>
						<td>
							<?php echo  $row_rsselecionausuariolog['usuariolog']?>
						</td>
						<td>
							<?php echo  $row_rsselecionausuariolog['ipusuariolog']?>
						</td>
						<td>
							<?php echo  $row_rsselecionausuariolog['obs']?>
						</td>
						<td>
                                                        <a href="javascript:func()" onclick="confirmacao('<?php echo $row_rsselecionausuariolog['idusuariolog']?>')"><input type="image"src="/site/images/delete.gif" width="30" height="30" name="Submit3" value="Excluir"></a>
						</td>
					</tr>
                                         <?php     
                    
                    }
        ?>
					
					
					
					
				</tbody>
			</table>
			
		</div>
	</div>
</div>
</body>
</html>
<script language="Javascript">
function confirmacao(id) {
     var resposta = confirm("Deseja realmente remover este usuario?");
 
     if (resposta == true) {
          window.location.href = "/site/gestorserver/log/action_exclui_usuario_log.php?id="+id;
     }
}
</script>