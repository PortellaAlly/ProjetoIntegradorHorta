<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Informações dos Sensores</title>
</head>
<body>
    <h1>Dados dos Sensores</h1>
    <p>Bem-vindo, <?php echo $_SESSION['usuario']; ?>! (Nível <?php echo $_SESSION['nivel']; ?>)</p>
    <a href="index.php">Voltar</a> | <a href="logout.php">Sair</a>
    <!-- Dados dos sensores aqui -->
</body>
</html>