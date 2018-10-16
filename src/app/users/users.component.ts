import {Component, OnInit} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs';
import {environment} from '../../environments/environment';

@Component({
    selector: 'app-users',
    templateUrl: './users.component.html',
    styleUrls: ['./users.component.scss']
})
export class UsersComponent implements OnInit {
    public users: any;

    constructor(private http: HttpClient) {}

    ngOnInit() {
        this.http.get(environment.baseAPIUrl + 'users')
            .subscribe((response) => {
                this.users = response;
            });
    }

}
