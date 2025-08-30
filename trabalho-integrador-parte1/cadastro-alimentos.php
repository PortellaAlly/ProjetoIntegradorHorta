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
    <title>Cadastro de Alimentos</title>
</head>
<body>
    <h1>Cadastrar Alimento</h1>
    <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']); ?>! (Nível <?php echo $_SESSION['nivel']; ?>)</p>
    
    <!-- Formulário de cadastro de alimentos -->
    <form method="POST" action="processar_cadastro_alimento.php">
        <fieldset>
            <legend>Dados do Alimento</legend>
            
            <label for="nome">Nome do Alimento:</label>
            <input type="text" id="nome" name="nome" required><br><br>
            
            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria" required>
                <option value="">Selecione...</option>
                <option value="frutas">Frutas</option>
                <option value="vegetais">Vegetais</option>
                <option value="carnes">Carnes</option>
                <option value="laticinios">Laticínios</option>
                <option value="graos">Grãos</option>
                <option value="bebidas">Bebidas</option>
                <option value="outros">Outros</option>
            </select><br><br>
            
            <label for="calorias">Calorias (por 100g):</label>
            <input type="number" id="calorias" name="calorias" step="0.1" min="0"><br><br>
            
            <label for="proteinas">Proteínas (g por 100g):</label>
            <input type="number" id="proteinas" name="proteinas" step="0.1" min="0"><br><br>
            
            <label for="carboidratos">Carboidratos (g por 100g):</label>
            <input type="number" id="carboidratos" name="carboidratos" step="0.1" min="0"><br><br>
            
            <label for="gorduras">Gorduras (g por 100g):</label>
            <input type="number" id="gorduras" name="gorduras" step="0.1" min="0"><br><br>
            
            <label for="descricao">Descrição:</label><br>
            <textarea id="descricao" name="descricao" rows="4" cols="50"></textarea><br><br>
            
            <input type="submit" value="Cadastrar Alimento">
            <input type="reset" value="Limpar Formulário">
        </fieldset>
    </form>
    
    <br>
    <a href="index.php">Voltar ao Início</a> | <a href="logout.php">Sair</a>
</body>
</html>