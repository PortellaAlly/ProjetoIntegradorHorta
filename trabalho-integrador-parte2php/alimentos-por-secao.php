<?php

    // ENDPOINT PHP PARA CONSULTAR E RETORNAR 
    // ALIMENTOS AGRUPADOS POR SEÇÃO DA HORTA.

    // Configurando o cabeçalho para retornar um arquivo json
    header("Content-type: application/json; charset=utf-8");

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