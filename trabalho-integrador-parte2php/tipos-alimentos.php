<?php

    // ENDPOINT PHP PARA CONSULTAR E RETORNAR 
    // TODOS OS TIPOS ÚNICOS DE ALIMENTOS.

    // Configurando o cabeçalho para retornar um arquivo json
    header("Content-type: application/json; charset=utf-8");

    $local = "localhost";
    $admin = "root";
    $senha = "";
    $based = "horta";
    
    $conexao = mysqli_connect($local, $admin, $senha, $based);
    $sql = "SELECT DISTINCT tipo_alimento FROM alimentos ORDER BY tipo_alimento";

    $resultados = mysqli_query($conexao, $sql);
    
    $tipos = [];
    while($linha = mysqli_fetch_assoc($resultados)){
        $tipos[] = $linha;
    }

    echo json_encode(["tipos" => $tipos]);

?>