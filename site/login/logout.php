<?php
	session_start(); // Inicia a sess�o
	session_destroy(); // Destr�i a sess�o limpando todos os valores salvos
	header("Location: /site/gestorserver/log/"); exit; // Redireciona o visitante
?>
<link href="/site/style.css" rel="stylesheet" type="text/css" media="all" />
