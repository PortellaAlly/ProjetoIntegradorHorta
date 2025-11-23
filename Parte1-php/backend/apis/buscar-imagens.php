<?php
session_start();
header('Content-Type: application/json');

// Verificar se está logado
if (!isset($_SESSION['usuario'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Usuário não autenticado.'
    ]);
    exit;
}

// Configuração do banco de dados
$local = "localhost";
$admin = "root";
$senha = "";
$based = "horta";

$conexao = mysqli_connect($local, $admin, $senha, $based);

if (!$conexao) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro na conexão com o banco de dados.'
    ]);
    exit;
}

// Buscar todas as imagens, ordenadas da mais recente para a mais antiga
$sql = "SELECT id, nome_arquivo, titulo, descricao, usuario, data_upload, secao_horta 
        FROM imagens 
        ORDER BY data_upload DESC";

$resultado = mysqli_query($conexao, $sql);

if (!$resultado) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao buscar imagens: ' . mysqli_error($conexao)
    ]);
    exit;
}

$imagens = [];
while ($linha = mysqli_fetch_assoc($resultado)) {
    $imagens[] = $linha;
}

if (count($imagens) > 0) {
    echo json_encode([
        'success' => true,
        'imagens' => $imagens,
        'total' => count($imagens)
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Nenhuma imagem foi enviada ainda.',
        'imagens' => []
    ]);
}

mysqli_close($conexao);
?>