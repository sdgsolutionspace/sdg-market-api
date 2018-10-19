import { Component, OnInit } from '@angular/core';
import { ApiUserService } from '../services/api/api-user.service';
import { User } from '../interfaces/user';
import { ToastrService } from 'ngx-toastr';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-user-show',
  templateUrl: './user-show.component.html',
  styleUrls: ['./user-show.component.scss']
})
export class UserShowComponent implements OnInit {

  public user: User;

  constructor(private apiUser: ApiUserService, private toastr: ToastrService, private route: ActivatedRoute) { }

  ngOnInit() {
    let username = this.route.snapshot.params["username"];
    this.apiUser.get(username).toPromise().then(user => {
      this.user = user;
    }).catch(err => {
      this.toastr.error("Unable to fetch the users", "An error occurred");
    })
  }

}
