import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { RoutingModule } from './routing.module';

import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { RepositoriesComponent } from './repositories/repositories.component';

@NgModule( {
  declarations: [
    AppComponent,
    LoginComponent,
    RepositoriesComponent
  ],
  imports: [
    BrowserModule,
    RoutingModule
  ],
  providers: [],
  bootstrap: [ AppComponent ]
} )
export class AppModule {
}
