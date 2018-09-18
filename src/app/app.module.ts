import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { RoutingModule } from './routing.module';

import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { RepositoriesComponent } from './repositories/repositories.component';
import { AddRepositoryComponent } from './repositories/add-repository/add-repository.component';
import { EditRepositoryComponent } from './repositories/edit-repository/edit-repository.component';

@NgModule( {
  declarations: [
    AppComponent,
    LoginComponent,
    RepositoriesComponent,
    AddRepositoryComponent,
    EditRepositoryComponent
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
