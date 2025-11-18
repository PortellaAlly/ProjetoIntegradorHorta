<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto Integrador</title>
    <style>
        /* Paleta horta: verdes, marrom claro, creme */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f1e1; /* creme */
            color: #2f3e1e; /* verde escuro */
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff; /* branco */
            border: 2px solid #7a9a57; /* verde médio */
            border-radius: 8px;
        }

        h1 {
            color: #3b5d2f; /* verde intenso */
            text-align: center;
        }

        .welcome {
            text-align: center;
            margin-bottom: 20px;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu li {
            margin: 10px 0;
        }

        .menu a {
            display: block;
            text-decoration: none;
            background-color: #a4c639; /* verde vivo */
            color: #fff;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .menu a:hover {
            background-color: #7a9a57; /* verde médio */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Trabalho Integrador - Parte 1</h1>
        <p class="welcome">
            Bem-vindo, <strong><?php echo $_SESSION['usuario']; ?></strong> 
            (Nível <?php echo $_SESSION['nivel']; ?>)
        </p>
        <ul class="menu">
            <li><a href="cadastro-alimentos.php">Cadastro de Alimentos (Nível 1)</a></li>
            <li><a href="sensores.php">Informações dos Sensores (Níveis 1 e 2)</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </div>
</body>
</html>
