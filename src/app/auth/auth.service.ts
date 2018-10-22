import { Injectable, EventEmitter } from '@angular/core';
import { Router } from '@angular/router';
import { environment } from '../../environments/environment';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { UserService } from "../services/api/user.service";
import { User } from '../interfaces/user';

@Injectable()
export class AuthService {
    public authenticationEvent: EventEmitter<User> = new EventEmitter();
    constructor(private router: Router, private http: HttpClient, private userService: UserService) {
    }

    login() {
        let url = `${environment.githubAuth.URL}?scope=${environment.githubAuth.SCOPE}`;
        url += `&state=${this.generateRandomState()}&response_type=code&approval_prompt=auto`;
        url += `&redirect_uri=${environment.githubAuth.REDIRECT_URI}&client_id=${environment.githubAuth.CLIENT_ID}`;

        window.location.href = url;
    }

    verifyCodeAndState(code, state): Observable<any> {
        const url = environment.baseUrl + '/connect/github/check';
        return this.http.get(`${url}?code=${code}&state=${state}`);
    }

    logout() {
        localStorage.removeItem(environment.localStorageJWT);
        localStorage.removeItem("CURRENT_USER");
        this.authenticationEvent.emit(null);
    }

    logInUser(response) {
        localStorage.setItem(environment.localStorageJWT, response.token);
        this.userService.getMe().toPromise().then(user => {
            this.authenticationEvent.emit(user);
            console.log("LOGINNNN", user);
            if (user) {
                localStorage.setItem("CURRENT_USER", JSON.stringify(user));
            } else {
                this.logout();
            }

        }).catch(err => {
            console.log("authentication error");
        })

    }

    generateRandomState() {
        return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    }

}
