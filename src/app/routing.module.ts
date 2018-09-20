import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { RepositoriesComponent } from './repositories/repositories.component';
import { AddRepositoryComponent } from './repositories/add-repository/add-repository.component';
import { EditRepositoryComponent } from './repositories/edit-repository/edit-repository.component';
import { AuctionsComponent } from './auctions/auctions.component';
import { ProjectAuctionComponent } from './auctions/project-auction/project-auction.component';

/**
 * Route constant
 */
const routes: Routes = [
  { path: '', redirectTo: 'login', pathMatch: 'full' },
  { path: 'login', component: LoginComponent },
  { path: 'repositories', component: RepositoriesComponent },
  { path: 'repositories/add', component: AddRepositoryComponent },
  { path: 'repositories/edit/:id', component: EditRepositoryComponent },
  { path: 'auctions', component: AuctionsComponent },
  { path: 'auctions/project/:id', component: ProjectAuctionComponent },
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
