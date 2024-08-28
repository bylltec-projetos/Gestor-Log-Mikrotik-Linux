
<form action="/site/gestorserver/log/action_add_novo_usuario.php" method="POST">

<table border="0" align="center">
    <thead>
        <tr>
            <th>Adionar novo usuario </th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><img src="/site/images/user.png" width="20" height="20" alt="user"/> Nome do Usuario:</td>
            <td><input type="text" name="nomeusuario" value="" />Ex: Maria, Atendente1 ou Cliente Joao</td>
        </tr>
         <tr>
            <td><img src="/site/images/ip.png" width="20" height="20" alt="ipusuario"/> IP do Usuario:</td>
            <td><input type="text" name="ipusuario" value="" />Um ip ex: 192.168.6.20</td>
        </tr>
          <tr>
            <td>Observação:</td>
            <td><input type="text" name="obs" value="" />Ex: Usuario de telnet e SSH</td>
        </tr>    
        
        <tr>
            <td><input type="submit" value="Salvar" /></td>
            <td>Obs.: Neste menu e possivel definir os usuario a ser filtrado no relatorio</td>
        </tr>
    </tbody>
</table>
</form>