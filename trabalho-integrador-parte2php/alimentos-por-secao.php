<?php

    // ENDPOINT PHP PARA CONSULTAR E RETORNAR 
    // ALIMENTOS AGRUPADOS POR SEÇÃO DA HORTA.

    // ✅ HEADERS CORS - ADICIONE SEMPRE NO INÍCIO
    header("Access-Control-Allow-Origin: *");
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
    $sql = "SELECT secao_circular, nome_alimento, tipo_alimento FROM alimentos ORDER BY secao_circular";

    $resultados = mysqli_query($conexao, $sql);
    
    $alimentos = [];
    while($linha = mysqli_fetch_assoc($resultados)){
        $alimentos[] = $linha;
    }

    echo json_encode(["alimentos" => $alimentos]);

?>