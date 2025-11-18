<?php

    // ENDPOINT PHP PARA CONSULTAR E RETORNAR TODOS
    // OS ALIMENTOS DA TABELA ALIMENTOS.

    // ✅ HEADERS CORS - ADICIONE SEMPRE NO INÍCIO
    header("Access-Control-Allow-Origin: http://localhost:4200");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Content-type: application/json; charset=utf-8");

    // Responde requisições OPTIONS (preflight)
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit(0);
    }

    $local = "localhost";
    $admin = "root";
    $senha = "";
    $based = "horta";
    
    $conexao = mysqli_connect($local, $admin, $senha, $based);
    $sql = "SELECT * FROM alimentos";

    $resultados = mysqli_query($conexao, $sql);
    
    $alimentos = [];
    while($linha = mysqli_fetch_assoc($resultados)){
        $alimentos[] = $linha;
    }

    echo json_encode(["alimentos" => $alimentos]);

?>