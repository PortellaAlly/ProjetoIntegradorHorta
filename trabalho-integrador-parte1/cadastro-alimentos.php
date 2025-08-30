<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}

// Verificar se é nível 1
if ($_SESSION['nivel'] != 1) {
    echo "ACESSO NEGADO! Apenas administradores nível 1 podem cadastrar alimentos.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Alimentos</title>
</head>
<body>
    <h1>Cadastrar Alimento</h1>
    <p>Bem-vindo, <?php echo $_SESSION['usuario']; ?>! (Nível <?php echo $_SESSION['nivel']; ?>)</p>
    <!-- Seu formulário de cadastro aqui -->
</body>
</html>