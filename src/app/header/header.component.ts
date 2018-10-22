import { Component, OnInit } from '@angular/core';
import { User } from '../interfaces/user';
import { AuthService } from '../auth/auth.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {

  public currentUser: User;

  constructor(private authService: AuthService) { }

  ngOnInit() {
    this.currentUser = localStorage.getItem("CURRENT_USER") ? JSON.parse(localStorage.getItem("CURRENT_USER")) : null;
    this.authService.authenticationEvent.subscribe(user => {
      console.log("New user fired", user);
      this.currentUser = user;
    });
  }

}
