import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './login/login.component';

/**
 * Route constant
 */
const routes: Routes = [
  { path: '', redirectTo: 'login', pathMatch: 'full' },
  { path: 'login', component: LoginComponent },
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
