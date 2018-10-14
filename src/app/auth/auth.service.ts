import {Injectable} from '@angular/core';
import {Router} from '@angular/router';
import {environment} from '../../environments/environment';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {UserService} from "../services/api/user.service";

@Injectable()
export class AuthService {
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
    }

    logInUser(response) {
        localStorage.setItem(environment.localStorageJWT, response.token);
    }

    async getMe(): Promise<any> {
        return await this.userService.getMe().toPromise();
    }

    generateRandomState() {
        return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
    }

}
