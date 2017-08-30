<!DOCTYPE html>
<html>

<head>
  <title>Ola!</title>
  <link href="/site/style.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="0" height="0" border="0" align="center">
  <tr>
    <td><img src="/site/images/chave.jpg" alt="cadeado" width="102" height="102" /></td>
    <td>Voc&ecirc; precisa estar logado para acessar esta pagina.</td>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><form action="/site/login/validacao.php" method="post">
      <fieldset>
      <legend>Dados de Login</legend>
        <label for="txUsuario">Usuario</label>
      <input type="text" name="usuario" id="txUsuario" maxlength="25" />
      <label for="txSenha">Senha</label>
      <input type="password" name="senha" id="txSenha" />
      <input name="submit" type="submit" value="Entrar" />
      </fieldset>
    </form></td>
    <td>&nbsp;</td>
  </tr>
 
</table>
</body>

</html>
