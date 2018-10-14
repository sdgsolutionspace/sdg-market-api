import {Component, OnInit} from '@angular/core';
import {AuthService} from '../../auth/auth.service';
import {ActivatedRoute, Router} from '@angular/router';

@Component({
    selector: 'app-callback',
    templateUrl: './callback.component.html',
    styleUrls: ['./callback.component.scss']
})
export class CallbackComponent implements OnInit {

    constructor(private auth: AuthService, private route: ActivatedRoute, private router: Router) {
    }

    ngOnInit() {
        const code: string = this.route.snapshot.queryParamMap.get('code');
        const state: string = this.route.snapshot.queryParamMap.get('state');
        this.auth.verifyCodeAndState(code, state)
            .subscribe(
                response => {
                    this.auth.logInUser(response);
                    this.router.navigate(['/auctions']);
                },
                error => {
                    // TODO: Better error handling
                    alert('Github Auth Error!');
                });
    }

}
