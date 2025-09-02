import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

interface Alimento {
  nome_alimento: string;
  nome_cientifico: string;
  tipo_alimento: string;
  secao_circular: string;
}

interface ContagemTipo {
  tipo_alimento: string;
  quantidade: number;
}

@Component({
  selector: 'app-alimentos',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './alimentos.html',
  styleUrl: './alimentos.css',
})
export class Alimentos implements OnInit {
  // Arrays para armazenar dados
  alimentos: Alimento[] = [];
  alimentosFiltrados: Alimento[] = [];
  tiposUnicos: string[] = [];
  contagemPorTipo: ContagemTipo[] = [];
  alimentosPorSecao: Alimento[] = [];

  // Variáveis para controle de filtros e visualização
  tipoSelecionado: string = '';
  secaoSelecionada: string = '';
  visualizacaoAtual: string = 'lista'; // 'lista', 'tipos', 'secoes', 'estatisticas'
  termoPesquisa: string = '';

  // Variáveis de controle
  carregando: boolean = false;
  erro: string = '';

  // URL base dos seus endpoints PHP - XAMPP
  private baseUrl =
    'http://localhost/ProjetoIntegradorHorta/trabalho-integrador-parte2php/';

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.carregarDados();
  }

  // Carrega todos os dados necessários
  async carregarDados() {
    this.carregando = true;
    this.erro = '';

    try {
      // Carrega dados dos endpoints PHP
      await this.carregarAlimentos();
      await this.carregarTipos();
      await this.carregarContagem();
      await this.carregarAlimentosPorSecao();
    } catch (error: any) {
      this.erro =
        'Erro ao carregar dados: ' + (error.message || JSON.stringify(error));
      console.error('Erro detalhado:', error);
    } finally {
      this.carregando = false;
    }
  }

  // Carrega todos os alimentos do endpoint alimentos.php
  carregarAlimentos(): Promise<void> {
    return new Promise((resolve, reject) => {
      this.http.get<any>(`${this.baseUrl}alimentos.php`).subscribe({
        next: (dados) => {
          console.log('Dados recebidos de alimentos.php:', dados);
          this.alimentos = dados.alimentos || [];
          this.alimentosFiltrados = [...this.alimentos];
          resolve();
        },
        error: (error) => {
          console.error('Erro ao carregar alimentos:', error);
          reject(error);
        },
      });
    });
  }

  // Carrega tipos únicos do endpoint tipos-alimentos.php
  carregarTipos(): Promise<void> {
    return new Promise((resolve, reject) => {
      this.http.get<any>(`${this.baseUrl}tipos-alimentos.php`).subscribe({
        next: (dados) => {
          console.log('Dados recebidos de tipos-alimentos.php:', dados);
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
      this.http.get<any>(`${this.baseUrl}count-alimentos.php`).subscribe({
        next: (dados) => {
          console.log('Dados recebidos de count-alimentos.php:', dados);
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

  // Carrega alimentos por seção do endpoint alimentos-por-secao.php
  carregarAlimentosPorSecao(): Promise<void> {
    return new Promise((resolve, reject) => {
      this.http.get<any>(`${this.baseUrl}alimentos-por-secao.php`).subscribe({
        next: (dados) => {
          console.log('Dados recebidos de alimentos-por-secao.php:', dados);
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

  // MÉTODO CORRIGIDO: Retorna alimentos por tipo específico
  getAlimentosPorTipo(tipo: string): Alimento[] {
    return this.alimentos.filter(alimento => alimento.tipo_alimento === tipo);
  }

  // MÉTODO CORRIGIDO: Retorna alimentos por seção específica
  getAlimentosPorSecao(secao: string): Alimento[] {
    return this.alimentos.filter(alimento => alimento.secao_circular === secao);
  }

  // Filtra alimentos por tipo
  filtrarPorTipo() {
    if (this.tipoSelecionado === '') {
      this.alimentosFiltrados = [...this.alimentos];
    } else {
      this.alimentosFiltrados = this.alimentos.filter(
        (alimento) => alimento.tipo_alimento === this.tipoSelecionado
      );
    }
    this.aplicarPesquisa();
  }

  // Filtra alimentos por seção
  filtrarPorSecao() {
    if (this.secaoSelecionada === '') {
      this.alimentosFiltrados = [...this.alimentos];
    } else {
      this.alimentosFiltrados = this.alimentos.filter(
        (alimento) => alimento.secao_circular === this.secaoSelecionada
      );
    }
    this.aplicarPesquisa();
  }

  // Aplica pesquisa por nome
  aplicarPesquisa() {
    if (this.termoPesquisa.trim() === '') {
      return;
    }

    this.alimentosFiltrados = this.alimentosFiltrados.filter(
      (alimento) =>
        alimento.nome_alimento
          .toLowerCase()
          .includes(this.termoPesquisa.toLowerCase()) ||
        alimento.nome_cientifico
          .toLowerCase()
          .includes(this.termoPesquisa.toLowerCase())
    );
  }

  // Pesquisa em tempo real
  pesquisar() {
    this.filtrarPorTipo();
    this.filtrarPorSecao();
  }

  // Limpa todos os filtros
  limparFiltros() {
    this.tipoSelecionado = '';
    this.secaoSelecionada = '';
    this.termoPesquisa = '';
    this.alimentosFiltrados = [...this.alimentos];
  }

  // Muda a visualização atual
  mudarVisualizacao(tipo: string) {
    this.visualizacaoAtual = tipo;
  }

  // Obtém seções únicas para o filtro
  getSecoesUnicas(): string[] {
    return [...new Set(this.alimentos.map((a) => a.secao_circular))].sort();
  }

  // Recarrega todos os dados
  recarregar() {
    this.carregarDados();
  }

  // Função trackBy para otimizar o *ngFor
  trackByNome(index: number, alimento: Alimento): string {
    return alimento.nome_alimento;
  }
}