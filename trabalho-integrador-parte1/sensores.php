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
            'valor' => rand(200, 1000), // Entre 200 e 1000 lux
            'unidade' => 'lux',
            'secao' => 'A',
            'status' => 'Normal',
            'ultima_leitura' => date('d/m/Y H:i:s')
        ],
        [
            'nome' => 'Sensor de Umidade do Solo',
            'tipo' => 'Umidade Solo',
            'valor' => rand(30, 80), // Entre 30% e 80%
            'unidade' => '%',
            'secao' => 'B',
            'status' => rand(1, 10) > 8 ? 'Atenção' : 'Normal', // 20% chance de atenção
            'ultima_leitura' => date('d/m/Y H:i:s', strtotime('-' . rand(1, 30) . ' minutes'))
        ],
        [
            'nome' => 'Sensor de Temperatura',
            'tipo' => 'Temperatura',
            'valor' => rand(18, 35), // Entre 18°C e 35°C
            'unidade' => '°C',
            'secao' => 'C',
            'status' => 'Normal',
            'ultima_leitura' => date('d/m/Y H:i:s', strtotime('-' . rand(1, 15) . ' minutes'))
        ],
        [
            'nome' => 'Sensor de Umidade do Ar',
            'tipo' => 'Umidade Ar',
            'valor' => rand(45, 90), // Entre 45% e 90%
            'unidade' => '%',
            'secao' => 'D',
            'status' => 'Normal',
            'ultima_leitura' => date('d/m/Y H:i:s', strtotime('-' . rand(1, 60) . ' minutes'))
        ],
        [
            'nome' => 'Sensor de pH do Solo',
            'tipo' => 'pH',
            'valor' => rand(60, 75) / 10, // Entre 6.0 e 7.5
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
<html>
<head>
    <meta charset="UTF-8">
    <title>Informações dos Sensores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .sensor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .sensor-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 5px solid #3498db;
        }
        
        .sensor-card.atencao {
            border-left-color: #e74c3c;
        }
        
        .sensor-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .sensor-value {
            font-size: 2em;
            font-weight: bold;
            color: #3498db;
            margin: 10px 0;
        }
        
        .sensor-details {
            color: #666;
            font-size: 0.9em;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }
        
        .status.normal {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status.atencao {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .nav-buttons {
            margin-top: 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            text-align: center;
        }
        
        .nav-buttons a {
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            display: inline-block;
        }
        
        .nav-buttons a:hover {
            background-color: #2980b9;
        }
        
        .refresh-info {
            background-color: #e8f6ff;
            border: 1px solid #3498db;
            border-radius: 5px;
            padding: 10px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌱 Monitoramento da Horta - Dados dos Sensores</h1>
        <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario']); ?>! (Nível <?php echo $_SESSION['nivel']; ?>)</p>
    </div>
    
    <div class="refresh-info">
        <strong>💡 Dica:</strong> Os dados são simulados e atualizados a cada refresh da página!<br>
        <small>Última atualização: <?php echo date('d/m/Y H:i:s'); ?></small>
    </div>
    
    <div class="sensor-grid">
        <?php foreach ($sensores as $sensor): ?>
            <div class="sensor-card <?php echo $sensor['status'] == 'Atenção' ? 'atencao' : ''; ?>">
                <div class="sensor-title">
                    <?php echo $sensor['nome']; ?>
                </div>
                
                <div class="sensor-value">
                    <?php echo $sensor['valor']; ?> <?php echo $sensor['unidade']; ?>
                </div>
                
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
        <a href="javascript:location.reload()">🔄 Atualizar Dados</a>
        <a href="index.php">🏠 Voltar ao Início</a>
        <a href="logout.php">🚪 Sair</a>
    </div>
</body>
</html>