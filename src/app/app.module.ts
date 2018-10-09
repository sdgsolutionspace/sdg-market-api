import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {RoutingModule} from './routing.module';
import {AuthService} from './auth/auth.service';

import {AppComponent} from './app.component';
import {LoginComponent} from './login/login.component';
import {RepositoriesComponent} from './repositories/repositories.component';
import {AddRepositoryComponent} from './repositories/add-repository/add-repository.component';
import {EditRepositoryComponent} from './repositories/edit-repository/edit-repository.component';
import {AuctionsComponent} from './auctions/auctions.component';
import {ProjectAuctionComponent} from './auctions/project-auction/project-auction.component';
import {CallbackComponent} from './login/callback/callback.component';
import {UsersComponent} from './users/users.component';
import {HttpClientModule} from '@angular/common/http';

@NgModule({
    declarations: [
        AppComponent,
        LoginComponent,
        RepositoriesComponent,
        AddRepositoryComponent,
        EditRepositoryComponent,
        AuctionsComponent,
        ProjectAuctionComponent,
        UsersComponent,
        CallbackComponent
    ],
    imports: [
        BrowserModule,
        RoutingModule,
        HttpClientModule
    ],
    providers: [
        AuthService
    ],
    bootstrap: [AppComponent]
})
export class AppModule {
}
