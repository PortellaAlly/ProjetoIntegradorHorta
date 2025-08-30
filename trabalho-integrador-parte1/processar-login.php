<?php
    session_start();

    $local = "localhost";
    $admin = "root";
    $senha = "";
    $based = "horta";

    $conexao = mysqli_connect($local, $admin, $senha, $based);

    $sql  = "SELECT usuario, senha FROM login WHERE usuario = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    $login_usuario = $_POST["usuario"];
    $login_senha = $_POST["senha"];

    mysqli_stmt_bind_param($stmt, "s", $login_usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    $linha = mysqli_fetch_assoc($resultado);
    if (!$linha) {
        echo "Usuário ou senha inválidos.";
        exit();
    }
    $senha_hash = $linha['senha'];

    if (password_verify($login_senha, $senha_hash)) {
    $_SESSION['usuario'] = $linha['usuario'];
    header("Location: index.php");
    exit();
    } else {
    echo "Usuário ou senha inválidos.";
        exit();
    }

?>