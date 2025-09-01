import { Component } from '@angular/core';
import { bootstrapApplication } from '@angular/platform-browser';
import { appConfig } from './app.config';
import { Alimentos } from './app/alimentos/alimentos';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [Alimentos],
  template: `
    <app-alimentos></app-alimentos>
  `,
  styles: [
    `
    :host {
      display: block;
      min-height: 100vh;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
  `,
  ],
})
export class App {
  name = 'Sistema Horta IF';
}

bootstrapApplication(App, appConfig);
