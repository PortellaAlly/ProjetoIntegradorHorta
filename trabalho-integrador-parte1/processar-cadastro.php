<?php
    $local = "localhost";
    $admin = "root";
    $senha = "";
    $based = "horta";

    $conexao = mysqli_connect($local, $admin, $senha, $based);

    $login_usuario = $_POST["usuario"];
    $login_senha = $_POST["senha"];

    $sql = "INSERT INTO login (usuario, senha) VALUES (?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    $senha_criptografada = password_hash($login_senha, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "ss", $login_usuario, $senha_criptografada);

    echo "<!DOCTYPE html>
    <html lang='pt-br'>
    <head>
        <meta charset='UTF-8'>
        <title>Cadastro - Horta</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f2f9f1;
                color: #2e4d2c;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .container {
                background: #ffffff;
                border: 2px solid #4caf50;
                border-radius: 12px;
                padding: 30px;
                width: 350px;
                text-align: center;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }
            h1 {
                margin-bottom: 15px;
                color: #4caf50;
            }
            p {
                font-size: 16px;
                margin-top: 10px;
            }
            .success {
                color: #2e7d32;
                font-weight: bold;
            }
            .error {
                color: #c62828;
                font-weight: bold;
            }
            .highlight {
                background-color: #e8f5e9;
                padding: 5px 10px;
                border-radius: 6px;
                display: inline-block;
                margin-top: 8px;
            }
        </style>
    </head>
    <body>
        <div class='container'>";

    if (mysqli_stmt_execute($stmt)) {
        echo "<h1>🌱 Cadastro realizado!</h1>";
        echo "<p class='success'>Usuário cadastrado com sucesso!</p>";
        echo "<p>Usuário: <span class='highlight'>" . htmlspecialchars($login_usuario) . "</span></p>";
    } else {
        echo "<h1>⚠️ Erro!</h1>";
        echo "<p class='error'>Erro ao cadastrar usuário:</p>";
        echo "<p>" . mysqli_stmt_error($stmt) . "</p>";
    }

    echo "</div>
    </body>
    </html>";

    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
?>