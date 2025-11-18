// Importações necessárias do Angular
import { HttpClient } from '@angular/common/http'; // Cliente HTTP para fazer requisições
import { Component, OnInit } from '@angular/core'; // Decoradores e interface de ciclo de vida
import { CommonModule } from '@angular/common'; // Módulo com diretivas comuns (*ngIf, *ngFor)
import { FormsModule } from '@angular/forms'; // Módulo para formulários e ngModel

// Interface que define a estrutura de um alimento
interface Alimento {
  nome_alimento: string;    // Nome comum do alimento
  nome_cientifico: string;  // Nome científico em latim
  tipo_alimento: string;    // Categoria/tipo do alimento
  secao_circular: string;   // Seção da horta onde está plantado
}

// Interface para contagem de alimentos por tipo (usado em estatísticas)
interface ContagemTipo {
  tipo_alimento: string; // Nome do tipo
  quantidade: number;    // Quantidade de alimentos deste tipo
}

// Decorador que define este como um componente Angular
@Component({
  selector: 'app-alimentos',           // Tag HTML que representa este componente
  standalone: true,                    // Componente independente (não precisa de módulo)
  imports: [CommonModule, FormsModule], // Módulos necessários para funcionar
  templateUrl: './alimentos.html',     // Arquivo de template HTML
  styleUrl: './alimentos.css',         // Arquivo de estilos CSS
})
// Classe principal que implementa OnInit (executa código na inicialização)
export class Alimentos implements OnInit {
  
  // === ARRAYS PARA ARMAZENAR DADOS ===
  alimentos: Alimento[] = [];              // Array principal com todos os alimentos
  alimentosFiltrados: Alimento[] = [];     // Array com alimentos após aplicar filtros
  tiposUnicos: string[] = [];              // Array com tipos únicos de alimentos
  contagemPorTipo: ContagemTipo[] = [];    // Array com contagem de alimentos por tipo
  alimentosPorSecao: Alimento[] = [];      // Array com alimentos organizados por seção

  // === VARIÁVEIS PARA CONTROLE DE FILTROS E VISUALIZAÇÃO ===
  tipoSelecionado: string = '';        // Tipo selecionado no filtro (vazio = todos)
  secaoSelecionada: string = '';       // Seção selecionada no filtro (vazio = todas)
  visualizacaoAtual: string = 'lista'; // Visualização atual: 'lista', 'tipos', 'secoes', 'estatisticas'
  termoPesquisa: string = '';          // Termo digitado na busca

  // === VARIÁVEIS DE CONTROLE DE ESTADO ===
  carregando: boolean = false; // Indica se está carregando dados
  erro: string = '';          // Mensagem de erro (vazia se não há erro)

  // URL base onde estão os endpoints PHP no XAMPP
  private baseUrl =
    'http://localhost/ProjetoIntegradorHorta/trabalho-integrador-parte2php/';

  // Construtor: injeta o HttpClient para fazer requisições HTTP
  constructor(private http: HttpClient) {}

  // Método executado automaticamente quando o componente é inicializado
  ngOnInit() {
    this.carregarDados(); // Carrega todos os dados necessários
  }

  // === MÉTODO PRINCIPAL DE CARREGAMENTO ===
  // Carrega todos os dados necessários de forma assíncrona
  async carregarDados() {
    this.carregando = true; // Ativa indicador de carregamento
    this.erro = '';         // Limpa qualquer erro anterior

    try {
      // Carrega dados de todos os endpoints PHP em sequência
      await this.carregarAlimentos();        // Lista completa de alimentos
      await this.carregarTipos();           // Tipos únicos disponíveis
      await this.carregarContagem();        // Contagem por tipo para estatísticas
      await this.carregarAlimentosPorSecao(); // Alimentos organizados por seção
    } catch (error: any) {
      // Em caso de erro, captura e formata a mensagem
      this.erro =
        'Erro ao carregar dados: ' + (error.message || JSON.stringify(error));
      console.error('Erro detalhado:', error); // Log detalhado para debug
    } finally {
      this.carregando = false; // Desativa indicador de carregamento
    }
  }

  // === MÉTODOS DE CARREGAMENTO DE DADOS ESPECÍFICOS ===
  
  // Carrega todos os alimentos do endpoint alimentos.php
  carregarAlimentos(): Promise<void> {
    return new Promise((resolve, reject) => {
      // Faz requisição GET para o endpoint
      this.http.get<any>(`${this.baseUrl}alimentos.php`).subscribe({
        next: (dados) => {
          console.log('Dados recebidos de alimentos.php:', dados);
          this.alimentos = dados.alimentos || [];           // Armazena alimentos ou array vazio
          this.alimentosFiltrados = [...this.alimentos];    // Inicializa filtrados com todos
          resolve(); // Promessa resolvida com sucesso
        },
        error: (error) => {
          console.error('Erro ao carregar alimentos:', error);
          reject(error); // Promessa rejeitada em caso de erro
        },
      });
    });
  }

  // Carrega tipos únicos do endpoint tipos-alimentos.php
  carregarTipos(): Promise<void> {
    return new Promise((resolve, reject) => {
      this.http.get<any>(`${this.baseUrl}tipos-alimentos.php`).subscribe({
        next: (dados) => {
          // Mapeia o array de objetos para array de strings (apenas os nomes dos tipos)
          this.tiposUnicos = dados.tipos.map((item: any) => item.tipo_alimento);
          resolve();
        },
        error: (error) => {
          reject(error);
        },
      });
    });
  }

  // Carrega contagem por tipo do endpoint count-alimentos.php
  carregarContagem(): Promise<void> {
    return new Promise((resolve, reject) => {
      this.http.get<any>(`${this.baseUrl}count-alimentos.php`).subscribe({
        next: (dados) => {
          console.log('Dados recebidos de count-alimentos.php:', dados);
          this.contagemPorTipo = dados.contagem || []; // Para gráficos estatísticos
          resolve();
        },
        error: (error) => {
          reject(error);
        },
      });
    });
  }

  // Carrega alimentos por seção do endpoint alimentos-por-secao.php
  carregarAlimentosPorSecao(): Promise<void> {
    return new Promise((resolve, reject) => {
      this.http.get<any>(`${this.baseUrl}alimentos-por-secao.php`).subscribe({
        next: (dados) => {
          this.alimentosPorSecao = dados.alimentos || [];
          resolve();
        },
        error: (error) => {
          reject(error);
        },
      });
    });
  }

  // === MÉTODOS DE FILTRAGEM DE DADOS ===

  // MÉTODO CORRIGIDO: Retorna alimentos de um tipo específico
  getAlimentosPorTipo(tipo: string): Alimento[] {
    // Filtra array principal pelo tipo solicitado
    return this.alimentos.filter(alimento => alimento.tipo_alimento === tipo);
  }

  // MÉTODO CORRIGIDO: Retorna alimentos de uma seção específica
  getAlimentosPorSecao(secao: string): Alimento[] {
    // Filtra array principal pela seção solicitada
    return this.alimentos.filter(alimento => alimento.secao_circular === secao);
  }

  // Filtra alimentos por tipo selecionado no dropdown
  filtrarPorTipo() {
    if (this.tipoSelecionado === '') {
      // Se nenhum tipo selecionado, mostra todos
      this.alimentosFiltrados = [...this.alimentos];
    } else {
      // Filtra apenas alimentos do tipo selecionado
      this.alimentosFiltrados = this.alimentos.filter(
        (alimento) => alimento.tipo_alimento === this.tipoSelecionado
      );
    }
    this.aplicarPesquisa(); // Aplica também o filtro de pesquisa
  }

  // Filtra alimentos por seção selecionada no dropdown
  filtrarPorSecao() {
    if (this.secaoSelecionada === '') {
      // Se nenhuma seção selecionada, mostra todos
      this.alimentosFiltrados = [...this.alimentos];
    } else {
      // Filtra apenas alimentos da seção selecionada
      this.alimentosFiltrados = this.alimentos.filter(
        (alimento) => alimento.secao_circular === this.secaoSelecionada
      );
    }
    this.aplicarPesquisa(); // Aplica também o filtro de pesquisa
  }

  // Aplica pesquisa por nome nos alimentos já filtrados
  aplicarPesquisa() {
    // Se não há termo de pesquisa, não faz nada
    if (this.termoPesquisa.trim() === '') {
      return;
    }

    // Filtra os alimentos já filtrados pelos dropdowns
    this.alimentosFiltrados = this.alimentosFiltrados.filter(
      (alimento) =>
        // Busca no nome comum (case-insensitive)
        alimento.nome_alimento
          .toLowerCase()
          .includes(this.termoPesquisa.toLowerCase()) ||
        // OU busca no nome científico (case-insensitive)
        alimento.nome_cientifico
          .toLowerCase()
          .includes(this.termoPesquisa.toLowerCase())
    );
  }

  // Pesquisa em tempo real (chamada a cada tecla digitada)
  pesquisar() {
    // Reaplica todos os filtros na ordem correta
    this.filtrarPorTipo();    // Primeiro filtra por tipo
    this.filtrarPorSecao();   // Depois por seção
    // aplicarPesquisa() é chamada dentro dos métodos acima
  }

  // === MÉTODOS DE CONTROLE DA INTERFACE ===

  // Limpa todos os filtros e restaura estado inicial
  limparFiltros() {
    this.tipoSelecionado = '';      // Reseta filtro de tipo
    this.secaoSelecionada = '';     // Reseta filtro de seção
    this.termoPesquisa = '';        // Limpa campo de pesquisa
    this.alimentosFiltrados = [...this.alimentos]; // Mostra todos os alimentos novamente
  }

  // Muda a visualização atual (lista, tipos, seções, estatísticas)
  mudarVisualizacao(tipo: string) {
    this.visualizacaoAtual = tipo;
  }

  // Obtém seções únicas para popular dropdown de filtros
  getSecoesUnicas(): string[] {
    // Cria Set para eliminar duplicatas, depois converte para array e ordena
    return [...new Set(this.alimentos.map((a) => a.secao_circular))].sort();
  }

  // Recarrega todos os dados (usado no botão "Tentar Novamente")
  recarregar() {
    this.carregarDados();
  }

  // Função trackBy para otimizar performance do *ngFor
  // Ajuda Angular a identificar quais itens mudaram, foram adicionados ou removidos
  trackByNome(index: number, alimento: Alimento): string {
    return alimento.nome_alimento; // Usa nome como identificador único
  }
}
