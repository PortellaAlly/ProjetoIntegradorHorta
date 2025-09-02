// Importações necessárias do Angular
import { ApplicationConfig } from '@angular/core';        // Tipo para configuração da app
import { provideHttpClient } from '@angular/common/http'; // Provedor do cliente HTTP
import { importProvidersFrom } from '@angular/core';      // Função para importar provedores
import { FormsModule } from '@angular/forms';             // Módulo de formulários

// Configuração exportada da aplicação Angular
export const appConfig: ApplicationConfig = {
  providers: [
    provideHttpClient(),           // Configura o cliente HTTP para requisições
    importProvidersFrom(FormsModule) // Importa provedores do FormsModule para usar ngModel
  ],
};