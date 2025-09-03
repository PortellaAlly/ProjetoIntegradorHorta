// Definição dos pinos

// Pino analógico conectado à saída AO do módulo do sensor de chuva
const int pinoChuvaAO = A0;

// Pino analógico conectado ao meio do potenciômetro, que será usado para regular a intensidade da irrigação
const int pinoPot = A1;


// Variáveis globais

// Variável para armazenar o valor lido do sensor de chuva
int valorChuva = 0;

// Variável para armazenar o valor lido do potenciômetro
int valorPot = 0;

// Variável booleana para indicar se está chovendo ou não
bool chovendo = false;

// Se a leitura ficar abaixo de limiteLow → considera chovendo
// Se a leitura ficar acima de limiteHigh → considera seco
const int limiteLow = 300;  
const int limiteHigh = 400; 


// Função de leitura com média
// Faz várias leituras do sensor e tira a média, para reduzir ruído elétrico e flutuações rápidas
int lerMedia(int pino, int vezes = 10) {
  long soma = 0;
  for (int i = 0; i < vezes; i++) {
    soma += analogRead(pino); // lê o valor do pino analógico
    delay(5);                 // pequeno atraso entre leituras
  }
  return soma / vezes;        // retorna a média das leituras
}


// Função de configuração inicial
void setup() {
  // Inicia a comunicação serial para enviar dados ao computador (Monitor Serial)
  Serial.begin(9600);
}

// Função principal

void loop() {
  // Lê o valor médio do sensor de chuva (0 a 1023)
  valorChuva = lerMedia(pinoChuvaAO, 15);

  // Lê o valor bruto do potenciômetro (0 a 1023)
  valorPot = analogRead(pinoPot);

  // Aplica histerese para decidir se está chovendo ou não
  if (valorChuva < limiteLow) {
    chovendo = true;   // valores baixos indicam que a placa está molhada
  } else if (valorChuva > limiteHigh) {
    chovendo = false;  // valores altos indicam placa seca
  }

  // Mostra a leitura do sensor de chuva no Monitor Serial
  Serial.print("Valor do Sensor de Chuva: ");
  Serial.println(valorChuva);

  // Se estiver chovendo → desliga irrigação
  if (chovendo) {
    Serial.println("💧 Está chovendo, irrigação desligada.");
  } 

  // Caso contrário → liga irrigação e regula intensidade com o potenciômetro
  else {
    
    // Converte o valor do potenciômetro (0–1023) em porcentagem (0–100%)
    int intensidade = map(valorPot, 0, 1023, 0, 100);

    Serial.print("☀️ Tempo seco. Irrigação ativada no nível: ");
    Serial.print(intensidade);
    Serial.println("%");
  }

  // Linha separadora para facilitar leitura no Monitor Serial
  Serial.println("------------------------------");

  // Aguarda 1 segundo antes da próxima leitura
  delay(1000);
}
