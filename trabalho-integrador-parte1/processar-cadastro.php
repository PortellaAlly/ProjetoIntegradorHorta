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

    if (mysqli_stmt_execute($stmt)) {
    echo "Usuário cadastrado com sucesso!";
    echo "<br>Usuário: " . htmlspecialchars($login_usuario);
    } else {
    echo "Erro ao cadastrar usuário: " . mysqli_stmt_error($stmt);
    }

    // Fechar conexões
    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
?>