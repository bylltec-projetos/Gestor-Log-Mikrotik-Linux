<?php
session_start();

// Verifica se houve POST e se o usuário ou a senha são vazios
if (!empty($_POST) && (empty($_POST['usuario']) || empty($_POST['senha']))) {
    header("Location: index.php");
    exit;
}

include('../Connections/site.php');

try {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Validação do usuário/senha digitados
    $sql = "SELECT `iduser`, `nome`, `nivel` FROM `usuarios` WHERE (`usuario` = :usuario) AND (`senha` = :senha) AND (`status` = 'ativo') LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':senha', sha1($senha));
    $stmt->execute();

    if ($stmt->rowCount() != 1) {
        // Mensagem de erro quando os dados são inválidos e/ou o usuário não foi encontrado
        echo '<head><meta http-equiv=refresh content="0; URL=/site/login/index.php"></head>';
        echo "<script type='text/javascript'> alert('Login inválido!');</script>";
        exit;
    } else {
        // Salva os dados encontrados na variável $resultado
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Salva os dados encontrados na sessão
        $_SESSION['UsuarioID'] = $resultado['iduser'];
        $_SESSION['UsuarioNome'] = $resultado['nome'];
        $_SESSION['UsuarioNivel'] = $resultado['nivel'];
        $_SESSION['iduser'] = $resultado['iduser'];

        // Redireciona o visitante
        header("Location: /site/gestorserver/log/?pagina=status");
        exit;
    }
} catch (PDOException $e) {
    echo "Failed to connect to MySQL: " . $e->getMessage();
    exit();
}
?>
