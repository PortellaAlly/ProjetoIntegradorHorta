<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria de Imagens - Horta</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f1e1;
            color: #2f3e1e;
        }

        .header {
            background-color: #3b5d2f;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0 0 10px 0;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }

        .filters {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
        }

        .filters label {
            font-weight: bold;
            margin-right: 10px;
            color: #3b5d2f;
        }

        .filters select {
            padding: 8px 15px;
            border: 1px solid #7a9a57;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        .loading {
            text-align: center;
            padding: 40px;
            font-size: 1.2em;
            color: #7a9a57;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #a4c639;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .image-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .image-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .image-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            cursor: pointer;
        }

        .image-info {
            padding: 15px;
        }

        .image-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #3b5d2f;
            margin-bottom: 8px;
        }

        .image-description {
            color: #555;
            font-size: 0.95em;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .image-meta {
            font-size: 0.85em;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
            margin-top: 10px;
        }

        .image-meta span {
            display: inline-block;
            margin-right: 15px;
        }

        .section-badge {
            background-color: #a4c639;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 0.85em;
        }

        .empty-message {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .empty-message h2 {
            color: #7a9a57;
            margin-bottom: 15px;
        }

        .nav-buttons {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .nav-buttons a {
            text-decoration: none;
            padding: 12px 20px;
            margin: 0 10px;
            background-color: #a4c639;
            color: white;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
        }

        .nav-buttons a:hover {
            background-color: #7a9a57;
        }

        /* Modal para visualização de imagem */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 90%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 40px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #bbb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🖼️ Galeria de Imagens da Horta</h1>
        <p>Bem-vindo, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></p>
    </div>

    <div class="container">
        <div class="filters">
            <label for="filtroSecao">Filtrar por Seção:</label>
            <select id="filtroSecao">
                <option value="">Todas as Seções</option>
                <option value="A">Seção A</option>
                <option value="B">Seção B</option>
                <option value="C">Seção C</option>
                <option value="D">Seção D</option>
            </select>
        </div>

        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>Carregando imagens...</p>
        </div>

        <div id="gallery" class="gallery"></div>
    </div>

    <div class="nav-buttons">
        <a href="upload-imagens.php">📤 Enviar Nova Imagem</a>
        <a href="index.php">🏠 Voltar ao Início</a>
        <a href="../../backend/logout.php">🚪 Sair</a>
    </div>

    <!-- Modal para visualizar imagem -->
    <div id="imageModal" class="modal">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script>
        let todasImagens = [];

        // Carregar imagens ao carregar a página
        window.addEventListener('DOMContentLoaded', carregarImagens);

        // Filtro por seção
        document.getElementById('filtroSecao').addEventListener('change', function() {
            const secaoSelecionada = this.value;
            exibirImagens(secaoSelecionada);
        });

        // Função para carregar imagens via AJAX
        async function carregarImagens() {
            const loading = document.getElementById('loading');
            const gallery = document.getElementById('gallery');
            
            loading.style.display = 'block';
            gallery.innerHTML = '';
            
            try {
                const response = await fetch('../../backend/apis/buscar-imagens.php');
                const data = await response.json();
                
                loading.style.display = 'none';
                
                if (data.success) {
                    todasImagens = data.imagens;
                    exibirImagens('');
                } else {
                    gallery.innerHTML = `
                        <div class="empty-message">
                            <h2>📭 Nenhuma imagem encontrada</h2>
                            <p>${data.message}</p>
                            <p style="margin-top: 20px;">
                                <a href="upload-imagens.php" style="color: #3b5d2f; font-weight: bold;">
                                    Envie a primeira imagem da horta!
                                </a>
                            </p>
                        </div>
                    `;
                }
            } catch (error) {
                loading.style.display = 'none';
                gallery.innerHTML = `
                    <div class="empty-message">
                        <h2>❌ Erro ao carregar imagens</h2>
                        <p>Ocorreu um erro ao buscar as imagens. Tente novamente.</p>
                    </div>
                `;
            }
        }

        // Função para exibir imagens (com filtro opcional)
        function exibirImagens(secaoFiltro) {
            const gallery = document.getElementById('gallery');
            
            const imagensFiltradas = secaoFiltro 
                ? todasImagens.filter(img => img.secao_horta === secaoFiltro)
                : todasImagens;
            
            if (imagensFiltradas.length === 0) {
                gallery.innerHTML = `
                    <div class="empty-message">
                        <h2>📭 Nenhuma imagem encontrada</h2>
                        <p>Não há imagens ${secaoFiltro ? 'na seção ' + secaoFiltro : 'cadastradas'}.</p>
                    </div>
                `;
                return;
            }
            
            gallery.innerHTML = imagensFiltradas.map(img => `
                <div class="image-card">
                    <img src="../../uploads/${img.nome_arquivo}" 
                         alt="${img.titulo}"
                         onclick="openModal('../../uploads/${img.nome_arquivo}')">
                    <div class="image-info">
                        <div class="image-title">${img.titulo}</div>
                        <div class="image-description">${img.descricao || 'Sem descrição'}</div>
                        <div class="image-meta">
                            <span><strong>Seção:</strong> <span class="section-badge">${img.secao_horta}</span></span>
                            <span><strong>Por:</strong> ${img.usuario}</span>
                            <br>
                            <span><strong>Data:</strong> ${formatarData(img.data_upload)}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Formatar data
        function formatarData(dataString) {
            const data = new Date(dataString);
            return data.toLocaleDateString('pt-BR') + ' ' + data.toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'});
        }

        // Abrir modal
        function openModal(imagemSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = 'block';
            modalImg.src = imagemSrc;
        }

        // Fechar modal
        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Fechar modal ao clicar fora da imagem
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>