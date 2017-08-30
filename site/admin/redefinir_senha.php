<?php
// A sessão precisa ser iniciada em cada página diferente
if (!isset($_SESSION)) session_start();
$nivel_necessario = 5;
// Verifica se não há a variável da sessão que identifica o usuário
if (!isset($_SESSION['UsuarioID']) OR ($_SESSION['UsuarioNivel'] > $nivel_necessario)) {
	// Destrói a sessão por segurança
	session_destroy();
	// Redireciona o visitante de volta pro login
	header("Location: /site/login/index.php"); exit;
}
?>
<form action="/site/admin/action_redefinir_senha.php" method="POST">

<table  align="center"    border="0">
    <thead>
        <tr> 
            <th>Trocar senha</th>
            
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Nova senha:</td>
            <td><input type="password" name="nsenha" value="" /></td>
    
            
        </tr>
        <tr>
            <td>Repita a senha:</td>
             <td><input type="password" name="rsenha" value="" /></td>                       
        </tr>
        <tr><td><input type="submit" value="Salvar" /></td></tr>
    </tbody>
</table>
</form>