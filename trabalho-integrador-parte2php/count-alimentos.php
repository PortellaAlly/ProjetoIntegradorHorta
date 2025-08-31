<?php

    // ENDPOINT PHP PARA CONSULTAR E RETORNAR 
    // A QUANTIDADE TOTAL DE ALIMENTOS POR TIPO.

    // Configurando o cabeçalho para retornar um arquivo json
    header("Content-type: application/json; charset=utf-8");

    $local = "localhost";
    $admin = "root";
    $senha = "";
    $based = "horta";
    
    $conexao = mysqli_connect($local, $admin, $senha, $based);
    $sql = "SELECT tipo_alimento, COUNT(*) as quantidade FROM alimentos GROUP BY tipo_alimento ORDER BY quantidade DESC";

    $resultados = mysqli_query($conexao, $sql);
    
    $contagem = [];
    while($linha = mysqli_fetch_assoc($resultados)){
        $contagem[] = $linha;
    }

    echo json_encode(["contagem" => $contagem]);

?>