<?php
// A sessï¿½o precisa ser iniciada em cada pï¿½gina diferente
if (!isset($_SESSION)) session_start();
$nivel_necessario = 5;
// Verifica se nï¿½o hï¿½ a variï¿½vel da sessï¿½o que identifica o usuï¿½rio
if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel'] > $nivel_necessario)) {
	// Destrï¿½i a sessï¿½o por seguranï¿½a
	session_destroy();
	// Redireciona o visitante de volta pro login
	header("Location: /site/login/index.php"); exit;
}
?>
<?php 
function tamanho_arquivo($arquivo) {
    $tamanhoarquivo = filesize($arquivo);
 
    /* Medidas */
    $medidas = array('KB', 'MB', 'GB', 'TB');
 
    /* Se for menor que 1KB arredonda para 1KB */
    if($tamanhoarquivo < 999){
        $tamanhoarquivo = 1000;
    }
 
    for ($i = 0; $tamanhoarquivo > 999; $i++){
        $tamanhoarquivo /= 1024;
    }
 
    return round($tamanhoarquivo) . $medidas[$i - 1];
}
?>
<table class="table">
<tr>
<th colspan="4">Backups</th>
</tr>
<tr>
<td>
<a onClick="document.getElementById('pop').style.display='block';" href="/site/gestorserver/log/backuplog/action_backup.php" class="btn btn-primary btn-sm" >Efeturar backup agora clique aqui</a>
</td>
<td>
<a href="/site/gestorserver/log/backuplog/action_limpa_tabela_log.php" class="btn btn-danger btn-sm" >Limpar todos log clique aqui</a>
</td>
</tr>
<?php
	//echo 'GLOB'.PHP_EOL;
	//chdir( '.' );
	//chdir( '/var/www/html/Gestor-Log-Mikrotik-Linux/site/gestorserver/log/backuplog/' );
	chdir( '/var/backups/gestorlog/' );
	$arquivos = glob("{*.txt,*.sql,*tar.gz}", GLOB_BRACE);// aqui vai os tipos de arquivos por extensao a ser listado separado por virgula
	sort($arquivos);
	foreach($arquivos as $img){
		?>
		<tr>
		<td><?php echo $img; ?></td>
		<td><?php echo tamanho_arquivo($img); ?></td>
<td><a href="/site/gestorserver/log/backuplog/action_download_arquivo.php?arquivo=<?php echo $img; ?>" class="btn btn-primary btn-sm">Baixar</a></td>
<td><a href="/site/gestorserver/log/backuplog/action_restaura_backup.php?arquivo=<?php echo $img; ?>" class="btn btn-primary btn-sm">Restaurar</a></td>
<td><a href="/site/gestorserver/log/backuplog/action_exclui_arquivo.php?arquivo=<?php echo $img; ?>" class="btn btn-danger btn-sm">Excluir</a></td>
</tr>
<?php
	} 
		
	//echo PHP_EOL;



/*
	$types = array( 'php', 'jpg' );
	
    echo 'DIR FUNCTIONS'.PHP_EOL;
	if ( $handle = opendir('.') ) {
	    while ( $entry = readdir( $handle ) ) {
	    	$ext = strtolower( pathinfo( $entry, PATHINFO_EXTENSION) );
			if( in_array( $ext, $types ) ) echo $entry.PHP_EOL;
	    }
	    closedir($handle);
	}    
	echo PHP_EOL;

    echo 'DIRECTORY ITERATOR'.PHP_EOL;
	$path = '.';
	$dir = new DirectoryIterator($path);
	foreach ($dir as $fileInfo) {
    	$ext = strtolower( $fileInfo->getExtension() );
	    if( in_array( $ext, $types ) ) echo $fileInfo->getFilename().PHP_EOL;
	}    
*/

	?>


</table>

<table class="table">
<tr>
<th colspan="4">Backups das ultimas horas</th>
</tr>
<?php
	//echo 'GLOB'.PHP_EOL;
	//chdir( '.' );
	//chdir( '/var/www/html/Gestor-Log-Mikrotik-Linux/site/gestorserver/log/backuplog/' );
	chdir( '/var/backups/gestorlog/diario/' );
	$arquivos = glob("{*.txt,*.sql,*tar.gz}", GLOB_BRACE);// aqui vai os tipos de arquivos por extensao a ser listado separado por virgula
	sort($arquivos);
	foreach($arquivos as $img){
		?>
		<tr>
		<td><?php echo $img; ?></td>
		<td><?php echo tamanho_arquivo($img); ?></td>
<td><a href="/site/gestorserver/log/backuplog/action_download_arquivo.php?arquivo=<?php echo $img; ?>" class="btn btn-primary btn-sm">Baixar</a></td>
<td><a href="/site/gestorserver/log/backuplog/action_exclui_arquivo.php?arquivo=<?php echo $img; ?>&tipo=hora" class="btn btn-danger btn-sm">Excluir</a></td>
</tr>
<?php
	} 
		
	//echo PHP_EOL;



/*
	$types = array( 'php', 'jpg' );
	
    echo 'DIR FUNCTIONS'.PHP_EOL;
	if ( $handle = opendir('.') ) {
	    while ( $entry = readdir( $handle ) ) {
	    	$ext = strtolower( pathinfo( $entry, PATHINFO_EXTENSION) );
			if( in_array( $ext, $types ) ) echo $entry.PHP_EOL;
	    }
	    closedir($handle);
	}    
	echo PHP_EOL;

    echo 'DIRECTORY ITERATOR'.PHP_EOL;
	$path = '.';
	$dir = new DirectoryIterator($path);
	foreach ($dir as $fileInfo) {
    	$ext = strtolower( $fileInfo->getExtension() );
	    if( in_array( $ext, $types ) ) echo $fileInfo->getFilename().PHP_EOL;
	}    
*/

	?>


</table>

