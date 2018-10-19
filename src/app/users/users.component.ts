import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import { ApiUserService } from '../services/api/api-user.service';
import { User } from '../interfaces/user';
import { ToastrService } from 'ngx-toastr';

@Component({
    selector: 'app-users',
    templateUrl: './users.component.html',
    styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {
    public users: Array<User>;

    constructor(private http: HttpClient, private apiUser: ApiUserService, private toastr: ToastrService) { }

    ngOnInit() {
        this.apiUser.getAll().toPromise().then(users => {
            this.users = users;
        }).catch(err => {
            this.toastr.error("Unable to fetch the users", "An error occurred");
        })
    }

}
