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
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Acesso Negado</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f1e1; /* creme */
                color: #2f3e1e; /* verde escuro */
                text-align: center;
                padding: 50px;
            }
            h1 {
                color: #a94442; /* vermelho de alerta */
            }
            a {
                margin: 0 10px;
                text-decoration: none;
                color: #3b5d2f;
                font-weight: bold;
            }
            a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <h1>Acesso Negado</h1>
        <p><strong>ACESSO NEGADO!</strong> Apenas administradores nível 1 podem cadastrar alimentos.</p>
        <p>Você não tem permissão para acessar esta página.</p>
        <a href="index.php">Voltar</a> | <a href="logout.php">Sair</a>
    </body>
    </html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Alimentos</title>
    <style>
        /* Paleta horta */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f1e1; /* creme */
            color: #2f3e1e; /* verde escuro */
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 25px;
            background: #ffffff;
            border: 2px solid #7a9a57;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #3b5d2f;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            margin-bottom: 20px;
        }

        fieldset {
            border: 1px solid #7a9a57;
            border-radius: 6px;
            padding: 20px;
        }

        legend {
            font-weight: bold;
            color: #3b5d2f;
        }

        label {
            display: block;
            margin: 12px 0 6px;
            font-weight: bold;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #7a9a57;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"],
        input[type="reset"] {
            margin-top: 15px;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"] {
            background-color: #a4c639; /* verde vivo */
            color: white;
        }

        input[type="submit"]:hover {
            background-color: #7a9a57; /* verde médio */
        }

        input[type="reset"] {
            background-color: #ddd;
            color: #333;
        }

        input[type="reset"]:hover {
            background-color: #bbb;
        }

        .links {
            text-align: center;
            margin-top: 20px;
        }

        .links a {
            margin: 0 10px;
            text-decoration: none;
            color: #3b5d2f;
            font-weight: bold;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastrar Alimento</h1>
        <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']); ?>! (Nível <?php echo $_SESSION['nivel']; ?>)</p>
        
        <form method="POST" action="processar-cadastro-alimentos.php">
            <fieldset>
                <legend>Dados do Alimento</legend>
                
                <label for="nomecomum">Nome do Alimento:</label>
                <input type="text" id="nomecomum" name="nomecomum" required>
                
                <label for="nomecientifico">Nome Científico do Alimento:</label>
                <input type="text" id="nomecientifico" name="nomecientifico">
                
                <label for="categoria">Categoria:</label>
                <select id="categoria" name="categoria" required>
                    <option value="">Selecione...</option>
                    <option value="Verdura">Verdura</option>
                    <option value="Legume">Legume</option>
                    <option value="Fruto">Fruto</option>
                    <option value="Tempero">Tempero</option>
                </select>

                <label for="secao">Seção Circular:</label>
                <select id="secao" name="secao" required>
                    <option value="">Selecione...</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>

                <input type="submit" value="Cadastrar Alimento">
                <input type="reset" value="Limpar Formulário">
            </fieldset>
        </form>
        
        <div class="links">
            <a href="index.php">Voltar ao Início</a> | <a href="logout.php">Sair</a>
        </div>
    </div>
</body>
</html>
