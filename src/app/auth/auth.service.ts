import { Injectable } from '@angular/core';
import * as auth0 from 'auth0-js';
import { environment } from './../../environments/environment';
import { Router } from '@angular/router';

@Injectable()
export class AuthService {
  // Create Auth0 web auth instance
  auth0 = new auth0.WebAuth( {
    clientID: environment.auth.CLIENT_ID,
    domain: environment.auth.CLIENT_DOMAIN,
    responseType: 'id_token token',
    redirectUri: environment.auth.REDIRECT,
    audience: environment.auth.AUDIENCE,
    scope: environment.auth.SCOPE
  } );
  // Store authentication data
  userProfile: any;
  accessToken: string;
  authenticated: boolean;

  constructor( private router: Router ) {
    // Check session to restore login if not expired
    this.getAccessToken();
  }

  login() {
    // Auth0 authorize request
    this.auth0.authorize();
  }

  handleLoginCallback() {
    // When Auth0 hash parsed, get profile
    this.auth0.parseHash( ( err, authResult ) => {
      console.log( authResult );
      if ( authResult && authResult.accessToken ) {
        window.location.hash = '';
        this.getUserInfo( authResult );
      } else if ( err ) {
        console.error( `Error: ${err.error}` );
      }
      this.router.navigate( [ '/' ] );
    } );
  }

  getAccessToken() {
    this.auth0.checkSession( {}, ( err, authResult ) => {
      if ( authResult && authResult.accessToken ) {
        this.getUserInfo( authResult );
      } else if ( err ) {
        console.log( err );
        this.logout();
        this.authenticated = false;
      }
    } );
  }

  getUserInfo( authResult ) {
    // Use access token to retrieve user's profile and set session
    this.auth0.client.userInfo( authResult.accessToken, ( err, profile ) => {
      console.log( profile );
      if ( profile ) {
        this._setSession( authResult, profile );
      }
    } );
  }

  private _setSession( authResult, profile ) {
    const expTime = authResult.expiresIn * 1000 + Date.now();
    // Save authentication data and update login status subject
    localStorage.setItem( 'expires_at', JSON.stringify( expTime ) );
    this.accessToken = authResult.accessToken;
    this.userProfile = profile;
    this.authenticated = true;
  }

  logout() {
    // Remove auth data and update login status
    localStorage.removeItem( 'expires_at' );
    this.userProfile = undefined;
    this.accessToken = undefined;
    this.authenticated = false;
  }

  get isLoggedIn(): boolean {
    // Check if current date is before token
    // expiration and user is signed in locally
    const expiresAt = JSON.parse( localStorage.getItem( 'expires_at' ) );
    return Date.now() < expiresAt && this.authenticated;
  }

}
