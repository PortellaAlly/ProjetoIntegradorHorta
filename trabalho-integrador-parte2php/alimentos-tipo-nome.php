<?php

    // ENFPOINT PHP PARA CONSULTAR E RETORNAR SOMENTE
    // O NOME E TIPO DOS ALIMENTOS DA TABELA ALIMENTOS.

    // Configurando o cabeçalho para retornar um arquivo json
    header("Content-type: application/json; charset=utf-8");

    $local = "localhost";
    $admin = "root";
    $senha = "";
    $based = "horta";
    
    $conexao = mysqli_connect($local, $admin, $senha, $based);
    $sql = "SELECT nome_alimento , tipo_alimento FROM alimentos";

    $resultados = mysqli_query($conexao, $sql);
    
    $alimentos = [];
    while($linha = mysqli_fetch_assoc($resultados)){
        $alimentos[] = $linha;
    }

    echo json_encode(["alimentos" => $alimentos]);

?>