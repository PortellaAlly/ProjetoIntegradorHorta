<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['nivel'] != 1) {
    header("Location: Parte1-php\frontend\pages\login.html");
    exit;
}

$local = "localhost";
$admin = "root";
$senha = "";
$based = "horta";

$conexao = mysqli_connect($local, $admin, $senha, $based);

if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

$sql = "INSERT INTO alimentos (nome_alimento, nome_cientifico, tipo_alimento, secao_circular) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conexao, $sql);

$nomecomum = $_POST["nomecomum"];
$nomecientifico = $_POST["nomecientifico"];
$categoria = $_POST["categoria"];
$secao = $_POST["secao"];

mysqli_stmt_bind_param($stmt, "ssss", $nomecomum, $nomecientifico, $categoria, $secao);

if (mysqli_stmt_execute($stmt)) {
    echo "Alimento cadastrado com sucesso!<br>";
    echo "<a href='cadastro-alimentos.php'>Cadastrar outro</a> | ";
    echo "<a href='Parte1-php\frontend\pages\'>Voltar ao início</a>";
} else {
    echo "Erro ao cadastrar: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($conexao);
?>