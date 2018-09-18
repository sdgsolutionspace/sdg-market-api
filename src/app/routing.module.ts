import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { RepositoriesComponent } from './repositories/repositories.component';
import { AddRepositoryComponent } from './repositories/add-repository/add-repository.component';
import { EditRepositoryComponent } from './repositories/edit-repository/edit-repository.component';

/**
 * Route constant
 */
const routes: Routes = [
  { path: '', redirectTo: 'login', pathMatch: 'full' },
  { path: 'login', component: LoginComponent },
  { path: 'repositories', component: RepositoriesComponent },
  { path: 'repositories/add', component: AddRepositoryComponent },
  { path: 'repositories/edit/:id', component: EditRepositoryComponent },
  { path: '**', redirectTo: 'login' }
];

/**
 * Routing module
 */
@NgModule( {
  imports: [
    RouterModule.forRoot( routes )
  ],
  exports: [ RouterModule ]
} )
export class RoutingModule {
}
