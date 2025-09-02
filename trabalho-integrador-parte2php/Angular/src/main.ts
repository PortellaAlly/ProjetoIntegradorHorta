// Importa o decorador Component do Angular para criar componentes
import { Component } from '@angular/core';
// Importa a função para inicializar uma aplicação Angular standalone
import { bootstrapApplication } from '@angular/platform-browser';
// Importa a configuração da aplicação (providers, serviços globais)
import { appConfig } from './app.config';
// Importa o componente Alimentos que será usado como conteúdo principal
import { Alimentos } from './app/alimentos/alimentos';

// Decorador que define este como um componente Angular
@Component({
  // Nome do seletor HTML que representa este componente
  selector: 'app-root',
  // Indica que é um componente standalone (não precisa de NgModule)
  standalone: true,
  // Lista de outros componentes/módulos que este componente usa
  imports: [Alimentos],
  // Template HTML inline - define o que será renderizado
  template: `
    <app-alimentos></app-alimentos>
  `,
  // Estilos CSS inline aplicados apenas a este componente
  styles: [
    `
    // Selector :host se refere ao próprio elemento do componente
    :host {
      // Define como bloco para ocupar largura total
      display: block;
      // Altura mínima de 100% da viewport
      min-height: 100vh;
      // Gradiente de fundo roxo/azul
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
  `,
  ],
})
// Classe do componente principal da aplicação
export class App {
  // Propriedade que armazena o nome do sistema
  name = 'Sistema Horta IF';
}

// Inicializa a aplicação Angular com o componente App e a configuração appConfig
bootstrapApplication(App, appConfig);