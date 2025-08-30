<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}

// Verificar se é nível 1
if ($_SESSION['nivel'] != 1) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Acesso Negado</title>
    </head>
    <body>
        <h1>Acesso Negado</h1>
        <p>ACESSO NEGADO! Apenas administradores nível 1 podem cadastrar alimentos.</p>
        <p>Você não tem permissão para acessar esta página.</p>
        <a href="index.php">Voltar</a> | <a href="logout.php">Sair</a>
    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Alimentosssssssssssssssssssssssssssssss</title>
</head>
<body>
    <h1>Cadastrar Alimento</h1>
    <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']); ?>! (Nível <?php echo $_SESSION['nivel']; ?>)</p>
    
    <!-- Formulário de cadastro de alimentos -->
    <form method="POST" action="processar-cadastro-alimentos.php">
        <fieldset>
            <legend>Dados do Alimento</legend>
            
            <label for="nome">Nome do Alimento:</label>
            <input type="text" id="nome" name="nomecomum" required><br><br>
            <label for="nome">Nome do Cientifico Alimento:</label>
            <input type="text" id="nome" name="nomecientifico" required><br><br>
            
            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria" required>
                <option value="">Selecione...</option>
                <option value="frutas">Frutas</option>
                <option value="vegetais">Vegetais</option>
                <option value="erva">Laticínios</option>
                <option value="graos">Grãos</option>
            </select><br><br>

            <label for="Secao">Seção circular:</label>
            <select id="categoria" name="categoria" required>
                <option value="">Selecione...</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
            </select><br><br>
            
            <input type="submit" value="Cadastrar Alimento">
            <input type="reset" value="Limpar Formulário">
        </fieldset>
    </form>
    
    <br>
    <a href="index.php">Voltar ao Início</a> | <a href="logout.php">Sair</a>
</body>
</html>