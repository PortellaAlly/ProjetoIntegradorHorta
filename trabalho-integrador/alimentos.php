<?php

    // ENFPOINT PHP PARA CONSULTAR E RETORNAR TODOS
    // OS ALIMENTOS DA TABELA ALIMENTOS.

    // Configurando o cabeçalho para retornar um arquivo json
    header("Content-type: application/json; charset=utf-8");

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