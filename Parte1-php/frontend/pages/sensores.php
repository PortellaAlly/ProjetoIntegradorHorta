<?php
session_start();

// Verificar se está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit;
}

// Função para gerar dados simulados dos sensores
function gerarDadosSensores() {
    return [
        [
            'nome' => 'Sensor de Luminosidade',
            'tipo' => 'Luminosidade',
            'valor' => rand(200, 1000),
            'unidade' => 'lux',
            'secao' => 'A',
            'status' => 'Normal',
            'ultima_leitura' => date('d/m/Y H:i:s')
        ],
        [
            'nome' => 'Sensor de Umidade do Solo',
            'tipo' => 'Umidade Solo',
            'valor' => rand(30, 80),
            'unidade' => '%',
            'secao' => 'B',
            'status' => rand(1, 10) > 8 ? 'Atenção' : 'Normal',
            'ultima_leitura' => date('d/m/Y H:i:s', strtotime('-' . rand(1, 30) . ' minutes'))
        ],
        [
            'nome' => 'Sensor de Temperatura',
            'tipo' => 'Temperatura',
            'valor' => rand(18, 35),
            'unidade' => '°C',
            'secao' => 'C',
            'status' => 'Normal',
            'ultima_leitura' => date('d/m/Y H:i:s', strtotime('-' . rand(1, 15) . ' minutes'))
        ],
        [
            'nome' => 'Sensor de Umidade do Ar',
            'tipo' => 'Umidade Ar',
            'valor' => rand(45, 90),
            'unidade' => '%',
            'secao' => 'D',
            'status' => 'Normal',
            'ultima_leitura' => date('d/m/Y H:i:s', strtotime('-' . rand(1, 60) . ' minutes'))
        ],
        [
            'nome' => 'Sensor de pH do Solo',
            'tipo' => 'pH',
            'valor' => rand(60, 75) / 10,
            'unidade' => 'pH',
            'secao' => 'A',
            'status' => 'Normal',
            'ultima_leitura' => date('d/m/Y H:i:s', strtotime('-' . rand(30, 120) . ' minutes'))
        ]
    ];
}

$sensores = gerarDadosSensores();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Informações dos Sensores</title>
    <style>
        /* Paleta horta: verdes, creme, marrom claro */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f1e1; /* creme */
            color: #2f3e1e; /* verde escuro */
        }
        
        .header {
            background-color: #3b5d2f; /* verde intenso */
            color: white;
            padding: 15px;
            border-radius: 0 0 8px 8px;
        }
        
        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin: 20px;
        }
        
        .sensor-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            border-left: 5px solid #7a9a57; /* verde médio */
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        .sensor-card.atencao {
            border-left-color: #a94442; /* vermelho atenção */
        }
        
        .sensor-title {
            font-size: 1.1em;
            font-weight: bold;
            margin-bottom: 8px;
            color: #3b5d2f;
        }
        
        .sensor-value {
            font-size: 1.6em;
            font-weight: bold;
            color: #7a9a57; /* verde médio */
            margin: 10px 0;
        }
        
        .sensor-details {
            font-size: 0.9em;
            color: #555;
        }
        
        .status {
            margin-top: 10px;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: bold;
            display: inline-block;
        }
        
        .status.normal {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status.atencao {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .refresh-info {
            background-color: #e6f0d5;
            border: 1px solid #7a9a57;
            border-radius: 5px;
            padding: 10px;
            margin: 20px;
            text-align: center;
        }
        
        .nav-buttons {
            margin: 30px 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        .nav-buttons a {
            text-decoration: none;
            padding: 10px 18px;
            margin: 0 8px;
            background-color: #a4c639; /* verde vivo */
            color: white;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        
        .nav-buttons a:hover {
            background-color: #7a9a57; /* verde médio */
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dados dos Sensores</h1>
        <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']); ?>! (Nível <?php echo $_SESSION['nivel']; ?>)</p>
    </div>
    
    <div class="refresh-info">
        <strong>Atenção:</strong> Os dados são simulados e atualizados a cada refresh da página.<br>
        <small>Última atualização: <?php echo date('d/m/Y H:i:s'); ?></small>
    </div>
    
    <div class="sensor-grid">
        <?php foreach ($sensores as $sensor): ?>
            <div class="sensor-card <?php echo $sensor['status'] == 'Atenção' ? 'atencao' : ''; ?>">
                <div class="sensor-title"><?php echo $sensor['nome']; ?></div>
                <div class="sensor-value"><?php echo $sensor['valor']; ?> <?php echo $sensor['unidade']; ?></div>
                <div class="sensor-details">
                    <strong>Seção:</strong> <?php echo $sensor['secao']; ?><br>
                    <strong>Última leitura:</strong> <?php echo $sensor['ultima_leitura']; ?>
                </div>
                <div class="status <?php echo strtolower($sensor['status']); ?>">
                    <?php echo $sensor['status']; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="nav-buttons">
        <a href="javascript:location.reload()">Atualizar Dados</a>
        <a href="index.php">Voltar ao Início</a>
        <a href="logout.php">Sair</a>
    </div>
</body>
</html>
