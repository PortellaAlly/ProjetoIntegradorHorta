<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>siteeeeeeeee</title>
</head>
<body>
    <h1>Trabalho Integrador - Parte 1</h1>
    <p>Bem-vindo, <?php echo $_SESSION['usuario']; ?>! (Nível <?php echo $_SESSION['nivel']; ?>)</p>
    <ul>
        <li><a href="cadastro-alimentos.php">Cadastro de Alimentos (Nível 1)</a></li>
        <li><a href="sensores.php">Informações dos Sensores (Níveis 1 e 2)</a></li>
        <li><a href="logout.php">Sair</a></li>
    </ul>
</body>
</html>