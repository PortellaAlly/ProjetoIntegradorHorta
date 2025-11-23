<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: Parte1-php\frontend\pages\login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Imagens - Horta</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f1e1;
            color: #2f3e1e;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            padding: 25px;
            background: #ffffff;
            border: 2px solid #7a9a57;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #3b5d2f;
            margin-bottom: 10px;
        }

        .user-info {
            text-align: center;
            margin-bottom: 25px;
            color: #555;
        }

        .upload-form {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        label {
            display: block;
            margin: 15px 0 6px;
            font-weight: bold;
            color: #3b5d2f;
        }

        input[type="text"],
        textarea,
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #7a9a57;
            border-radius: 5px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        input[type="file"] {
            padding: 8px;
            cursor: pointer;
        }

        .file-info {
            font-size: 0.9em;
            color: #666;
            margin-top: 5px;
        }

        .preview-area {
            margin-top: 15px;
            text-align: center;
        }

        .preview-area img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            border: 2px solid #7a9a57;
            display: none;
        }

        button {
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #a4c639;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #7a9a57;
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .message {
            margin-top: 15px;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            display: none;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .nav-links {
            text-align: center;
            margin-top: 25px;
        }

        .nav-links a {
            margin: 0 10px;
            text-decoration: none;
            color: #3b5d2f;
            font-weight: bold;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 15px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #a4c639;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📸 Upload de Imagens da Horta</h1>
        <p class="user-info">
            Bem-vindo, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>
        </p>

        <form id="uploadForm" class="upload-form" enctype="multipart/form-data">
            <label for="titulo">Título da Foto:</label>
            <input type="text" id="titulo" name="titulo" required placeholder="Ex: Alfaces na Seção A">

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" placeholder="Descreva o que aparece na foto..."></textarea>

            <label for="secao">Seção da Horta:</label>
            <select id="secao" name="secao" required>
                <option value="">Selecione a seção...</option>
                <option value="A">Seção A</option>
                <option value="B">Seção B</option>
                <option value="C">Seção C</option>
                <option value="D">Seção D</option>
            </select>

            <label for="imagem">Selecione a Imagem:</label>
            <input type="file" id="imagem" name="imagem" accept="image/*" required>
            <p class="file-info">Formatos aceitos: JPG, PNG, GIF (máx. 5MB)</p>

            <div class="preview-area">
                <img id="preview" alt="Preview da imagem">
            </div>

            <button type="submit" id="submitBtn">📤 Fazer Upload</button>
        </form>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Enviando imagem...</p>
        </div>

        <div id="message" class="message"></div>

        <div class="nav-links">
            <a href="galeria.php">Ver Galeria</a> |
            <a href="index.php">Voltar ao Início</a> |
            <a href="../../backend/logout.php">Sair</a>
        </div>
    </div>

    <script>
        // Preview da imagem selecionada
        document.getElementById('imagem').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview');
            
            if (file) {
                // Validar tamanho
                if (file.size > 5 * 1024 * 1024) {
                    alert('Arquivo muito grande! O tamanho máximo é 5MB.');
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }

                // Mostrar preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Submit com AJAX
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const loading = document.getElementById('loading');
            const message = document.getElementById('message');
            
            // Desabilitar botão e mostrar loading
            submitBtn.disabled = true;
            loading.style.display = 'block';
            message.style.display = 'none';
            
            try {
                const response = await fetch('../../backend/apis/processar-upload.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                loading.style.display = 'none';
                message.style.display = 'block';
                
                if (result.success) {
                    message.className = 'message success';
                    message.textContent = '✅ ' + result.message;
                    
                    // Limpar formulário
                    document.getElementById('uploadForm').reset();
                    document.getElementById('preview').style.display = 'none';
                    
                    // Redirecionar para galeria após 2 segundos
                    setTimeout(() => {
                        window.location.href = 'galeria.php';
                    }, 2000);
                } else {
                    message.className = 'message error';
                    message.textContent = '❌ ' + result.message;
                    submitBtn.disabled = false;
                }
                
            } catch (error) {
                loading.style.display = 'none';
                message.style.display = 'block';
                message.className = 'message error';
                message.textContent = '❌ Erro ao enviar imagem. Tente novamente.';
                submitBtn.disabled = false;
            }
        });
    </script>
</body>
</html>