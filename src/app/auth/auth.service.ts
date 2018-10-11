import { Injectable } from '@angular/core';
import * as auth0 from 'auth0-js';
import { environment } from './../../environments/environment';
import { Router } from '@angular/router';

@Injectable()
export class AuthService {
  // Create Auth0 web auth instance
  auth0 = new auth0.WebAuth({
    clientID: environment.auth.CLIENT_ID,
    domain: environment.auth.CLIENT_DOMAIN,
    responseType: 'id_token token',
    redirectUri: environment.auth.REDIRECT,
    audience: environment.auth.AUDIENCE,
    scope: environment.auth.SCOPE
  });

  constructor(private router: Router) {
    // Check session to restore login if not expired
    this.getAccessToken();
  }

  login() {
    // Auth0 authorize request
    this.auth0.authorize();
  }

  handleLoginCallback() {
    // When Auth0 hash parsed, get profile
    this.auth0.parseHash((err, authResult) => {
      console.log(authResult);
      if (authResult && authResult.accessToken) {
        window.location.hash = '';
        this.getUserInfo(authResult);
      } else if (err) {
        console.error(`Error: ${err.error}`);
      }
    });
  }

  getAccessToken() {
    this.auth0.checkSession({}, (err, authResult) => {
      if (authResult && authResult.accessToken) {
        this.getUserInfo(authResult);
      } else if (err) {
        console.log(err);
        this.logout();
      }
    });
  }

  getUserInfo(authResult) {
    // Use access token to retrieve user's profile and set session
    this.auth0.client.userInfo(authResult.accessToken, (err, profile) => {
      console.log(profile);
      if (profile) {
        this._setSession(authResult, profile);
        this.router.navigate(['auctions']);
      }
    });
  }

  private _setSession(authResult, profile) {
    const expTime = authResult.expiresIn * 1000 + Date.now();
    // Save authentication data and update login status subject
    localStorage.setItem('expires_at', JSON.stringify(expTime));
    localStorage.setItem('authenticated', '1');
    localStorage.setItem('accessToken', JSON.stringify(authResult.accessToken));
    localStorage.setItem('userProfile', JSON.stringify(profile));
  }

  logout() {
    // Remove auth data and update login status
    localStorage.removeItem('expires_at');
    localStorage.removeItem('authenticated');
    localStorage.removeItem('userProfile');
    localStorage.removeItem('accessToken');
  }

  get isLoggedIn(): boolean {
    // Check if current date is before token
    // expiration and user is signed in locally
    const expiresAt = JSON.parse(localStorage.getItem('expires_at'));
    const authenticated = localStorage.getItem('authenticated') === '1';

    return Date.now() < expiresAt && authenticated;
  }

}
