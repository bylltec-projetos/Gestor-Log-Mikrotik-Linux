<?php
	session_start(); // Inicia a sessão
	session_destroy(); // Destrói a sessão limpando todos os valores salvos
	header("Location: /site/gestorserver/log/"); exit; // Redireciona o visitante
?>
<link href="/site/style.css" rel="stylesheet" type="text/css" media="all" />
