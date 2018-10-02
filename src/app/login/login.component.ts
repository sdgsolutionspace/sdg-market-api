import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from '../auth/auth.service';

@Component( {
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: [ './login.component.scss' ]
} )
export class LoginComponent implements OnInit {

  title = 'GitHub Trading';

  constructor( private router: Router, public auth: AuthService ) {
  }

  ngOnInit() {
  }

  connectGitHub() {
    this.auth.login();
  }

}
