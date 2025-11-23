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

// Verificar se o arquivo foi enviado
if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'message' => 'Nenhuma imagem foi enviada ou ocorreu um erro no upload.'
    ]);
    exit;
}

// Receber dados do formulário
$titulo = mysqli_real_escape_string($conexao, $_POST['titulo']);
$descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);
$secao = mysqli_real_escape_string($conexao, $_POST['secao']);
$usuario = $_SESSION['usuario'];

// Informações do arquivo
$arquivo = $_FILES['imagem'];
$nomeArquivo = $arquivo['name'];
$tamanhoArquivo = $arquivo['size'];
$tmpName = $arquivo['tmp_name'];
$extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

// Validar extensão
$extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
if (!in_array($extensao, $extensoesPermitidas)) {
    echo json_encode([
        'success' => false,
        'message' => 'Formato de arquivo não permitido. Use JPG, PNG ou GIF.'
    ]);
    exit;
}

// Validar tamanho (5MB)
if ($tamanhoArquivo > 5 * 1024 * 1024) {
    echo json_encode([
        'success' => false,
        'message' => 'Arquivo muito grande. O tamanho máximo é 5MB.'
    ]);
    exit;
}

// Criar diretório se não existir
$diretorioUpload = '../../uploads/';
if (!file_exists($diretorioUpload)) {
    mkdir($diretorioUpload, 0777, true);
}

// Gerar nome único para o arquivo
$nomeUnico = uniqid() . '_' . time() . '.' . $extensao;
$caminhoCompleto = $diretorioUpload . $nomeUnico;

// Mover arquivo para o diretório
if (move_uploaded_file($tmpName, $caminhoCompleto)) {
    // Inserir informações no banco de dados
    $sql = "INSERT INTO imagens (nome_arquivo, titulo, descricao, usuario, secao_horta) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    
    mysqli_stmt_bind_param($stmt, "sssss", $nomeUnico, $titulo, $descricao, $usuario, $secao);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'message' => 'Imagem enviada com sucesso!'
        ]);
    } else {
        // Se falhou no banco, deletar o arquivo
        unlink($caminhoCompleto);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao salvar informações no banco de dados.'
        ]);
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao mover o arquivo para o servidor.'
    ]);
}

mysqli_close($conexao);
?>