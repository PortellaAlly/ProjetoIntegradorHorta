// Importa o HttpClient para fazer requisições HTTP
import { HttpClient } from '@angular/common/http';
// Importa Component (decorador) e OnInit (interface do ciclo de vida)
import { Component, OnInit } from '@angular/core';
// Importa módulo comum com diretivas básicas (*ngFor, *ngIf, etc.)
import { CommonModule } from '@angular/common';
// Importa módulo para trabalhar com formulários e two-way binding [(ngModel)]
import { FormsModule } from '@angular/forms';

// Interface TypeScript que define a estrutura de um alimento
interface Alimento {
  nome_alimento: string;    // Nome popular do alimento
  nome_cientifico: string;  // Nome científico em latim
  tipo_alimento: string;    // Categoria (Verdura, Legume, Fruto, Tempero)
  secao_circular: string;   // Seção da horta onde está plantado
}

// Interface para representar contagem de alimentos por tipo
interface ContagemTipo {
  tipo_alimento: string;    // Nome do tipo
  quantidade: number;       // Quantidade de alimentos deste tipo
}

// Decorador que define este como um componente Angular
@Component({
  // Seletor HTML para usar o componente (<app-alimentos></app-alimentos>)
  selector: 'app-alimentos',
  // Componente standalone (não precisa estar em um NgModule)
  standalone: true,
  // Módulos/componentes que este componente precisa usar
  imports: [CommonModule, FormsModule],
  // Arquivo HTML externo com o template
  templateUrl: './alimentos.html',
  // Arquivo CSS externo com os estilos
  styleUrl: './alimentos.css',
})
// Classe principal do componente que implementa OnInit
export class Alimentos implements OnInit {
  
  // === ARRAYS PARA ARMAZENAR DADOS ===
  
  // Array principal com todos os alimentos carregados do backend
  alimentos: Alimento[] = [];
  // Array com alimentos filtrados (resultado dos filtros aplicados)
  alimentosFiltrados: Alimento[] = [];
  // Array com tipos únicos de alimentos (para o select de filtro)
  tiposUnicos: string[] = [];
  // Array com contagem de alimentos por tipo (para estatísticas)
  contagemPorTipo: ContagemTipo[] = [];
  // Array específico para organização por seção
  alimentosPorSecao: Alimento[] = [];

  // === VARIÁVEIS PARA CONTROLE DE FILTROS E VISUALIZAÇÃO ===
  
  // Tipo selecionado no filtro (vazio = todos)
  tipoSelecionado: string = '';
  // Seção selecionada no filtro (vazio = todas)
  secaoSelecionada: string = '';
  // Modo de visualização atual ('lista', 'tipos', 'secoes', 'estatisticas')
  visualizacaoAtual: string = 'lista';
  // Texto digitado na caixa de pesquisa
  termoPesquisa: string = '';

  // === VARIÁVEIS DE CONTROLE DE ESTADO ===
  
  // Indica se está carregando dados (para mostrar spinner)
  carregando: boolean = false;
  // Armazena mensagens de erro (vazio = sem erro)
  erro: string = '';

  // URL base dos endpoints PHP no XAMPP
  private baseUrl = 'http://localhost/ProjetoIntegradorHorta/trabalho-integrador-parte2php/';

  // Construtor: injeta o HttpClient para fazer requisições HTTP
  constructor(private http: HttpClient) {}

  // Método do ciclo de vida Angular - executado após inicialização
  ngOnInit() {
    // Carrega todos os dados necessários ao inicializar o componente
    this.carregarDados();
  }

  // === MÉTODOS PARA CARREGAMENTO DE DADOS ===

  // Método assíncrono principal que coordena o carregamento de todos os dados
  async carregarDados() {
    // Ativa indicador de carregamento
    this.carregando = true;
    // Limpa mensagens de erro anteriores
    this.erro = '';

    try {
      // Chama todos os métodos de carregamento em sequência
      await this.carregarAlimentos();        // Carrega lista completa
      await this.carregarTipos();           // Carrega tipos únicos
      await this.carregarContagem();        // Carrega estatísticas
      await this.carregarAlimentosPorSecao(); // Carrega organização por seção
    } catch (error: any) {
      // Se houver erro, captura e formata a mensagem
      this.erro = 'Erro ao carregar dados: ' + (error.message || JSON.stringify(error));
      // Log detalhado no console do navegador para debug
      console.error('Erro detalhado:', error);
    } finally {
      // Sempre desativa o indicador de carregamento, com ou sem erro
      this.carregando = false;
    }
  }

  // Carrega todos os alimentos do endpoint alimentos.php
  carregarAlimentos(): Promise<void> {
    // Retorna Promise para poder usar await no método chamador
    return new Promise((resolve, reject) => {
      // Faz requisição GET para o endpoint PHP
      this.http.get<any>(`${this.baseUrl}alimentos.php`).subscribe({
        // Callback para sucesso na requisição
        next: (dados) => {
          // Log dos dados recebidos para debug
          console.log('Dados recebidos de alimentos.php:', dados);
          // Extrai array de alimentos ou usa array vazio se não existir
          this.alimentos = dados.alimentos || [];
          // Inicializa array filtrado com todos os alimentos
          this.alimentosFiltrados = [...this.alimentos];
          // Resolve a Promise indicando sucesso
          resolve();
        },
        // Callback para erro na requisição
        error: (error) => {
          // Log do erro para debug
          console.error('Erro ao carregar alimentos:', error);
          // Rejeita a Promise passando o erro
          reject(error);
        },
      });
    });
  }

  // Carrega tipos únicos do endpoint tipos-alimentos.php
  carregarTipos(): Promise<void> {
    return new Promise((resolve, reject) => {
      // Requisição para endpoint específico de tipos
      this.http.get<any>(`${this.baseUrl}tipos-alimentos.php`).subscribe({
        next: (dados) => {
          console.log('Dados recebidos de tipos-alimentos.php:', dados);
          // Mapeia array de objetos para array de strings (apenas os nomes dos tipos)
          this.tiposUnicos = dados.tipos.map((item: any) => item.tipo_alimento);
          resolve();
        },
        error: (error) => {
          console.error('Erro ao carregar tipos:', error);
          reject(error);
        },
      });
    });
  }

  // Carrega contagem por tipo do endpoint count-alimentos.php
  carregarContagem(): Promise<void> {
    return new Promise((resolve, reject) => {
      // Requisição para endpoint de estatísticas
      this.http.get<any>(`${this.baseUrl}count-alimentos.php`).subscribe({
        next: (dados) => {
          console.log('Dados recebidos de count-alimentos.php:', dados);
          // Armazena array com contagens por tipo
          this.contagemPorTipo = dados.contagem || [];
          resolve();
        },
        error: (error) => {
          console.error('Erro ao carregar contagem:', error);
          reject(error);
        },
      });
    });
  }

  // Carrega alimentos organizados por seção
  carregarAlimentosPorSecao(): Promise<void> {
    return new Promise((resolve, reject) => {
      // Requisição para endpoint de alimentos por seção
      this.http.get<any>(`${this.baseUrl}alimentos-por-secao.php`).subscribe({
        next: (dados) => {
          console.log('Dados recebidos de alimentos-por-secao.php:', dados);
          // Armazena dados organizados por seção
          this.alimentosPorSecao = dados.alimentos || [];
          resolve();
        },
        error: (error) => {
          console.error('Erro ao carregar alimentos por seção:', error);
          reject(error);
        },
      });
    });
  }

  // === MÉTODOS DE FILTROS ===

  // Filtra alimentos pelo tipo selecionado
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
    // Aplica também o filtro de pesquisa por texto
    this.aplicarPesquisa();
  }

  // Filtra alimentos pela seção selecionada
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
    // Aplica também o filtro de pesquisa por texto
    this.aplicarPesquisa();
  }

  // Aplica filtro de pesquisa por texto no nome comum ou científico
  aplicarPesquisa() {
    // Se não há termo de pesquisa, não filtra
    if (this.termoPesquisa.trim() === '') {
      return;
    }

    // Filtra alimentos que contenham o termo no nome comum ou científico
    this.alimentosFiltrados = this.alimentosFiltrados.filter(
      (alimento) =>
        // Busca no nome comum (convertido para minúsculo)
        alimento.nome_alimento
          .toLowerCase()
          .includes(this.termoPesquisa.toLowerCase()) ||
        // OU busca no nome científico (convertido para minúsculo)
        alimento.nome_cientifico
          .toLowerCase()
          .includes(this.termoPesquisa.toLowerCase())
    );
  }

  // Método chamado quando usuário digita na caixa de pesquisa
  pesquisar() {
    // Reaplica todos os filtros para considerar o novo termo
    this.filtrarPorTipo();
    this.filtrarPorSecao();
  }

  // Remove todos os filtros aplicados
  limparFiltros() {
    // Reseta todas as variáveis de filtro
    this.tipoSelecionado = '';
    this.secaoSelecionada = '';
    this.termoPesquisa = '';
    // Mostra todos os alimentos novamente
    this.alimentosFiltrados = [...this.alimentos];
  }

  // === MÉTODOS DE NAVEGAÇÃO E VISUALIZAÇÃO ===

  // Muda o modo de visualização (lista, tipos, seções, estatísticas)
  mudarVisualizacao(tipo: string) {
    this.visualizacaoAtual = tipo;
  }

  // Obtém lista única de seções para usar nos filtros
  getSecoesUnicas(): string[] {
    // Cria Set para remover duplicatas, converte para array e ordena
    return [...new Set(this.alimentos.map((a) => a.secao_circular))].sort();
  }

  // Agrupa alimentos por seção para exibição organizada
  getAlimentosPorSecao() {
    // Objeto onde a chave é a seção e o valor é array de alimentos
    const secoes: { [key: string]: Alimento[] } = {};
    
    // Itera sobre alimentos organizados por seção
    this.alimentosPorSecao.forEach((alimento) => {
      // Se a seção ainda não existe no objeto, cria array vazio
      if (!secoes[alimento.secao_circular]) {
        secoes[alimento.secao_circular] = [];
      }
      // Adiciona alimento à seção correspondente
      secoes[alimento.secao_circular].push(alimento);
    });
    
    return secoes;
  }

  // === MÉTODOS AUXILIARES ===

  // Recarrega todos os dados (usado no botão "Tentar Novamente")
  recarregar() {
    this.carregarDados();
  }

  // Função de otimização para *ngFor - evita recriar elementos desnecessariamente
  trackByNome(index: number, alimento: Alimento): string {
    // Usa o nome do alimento como identificador único
    return alimento.nome_alimento;
  }
}