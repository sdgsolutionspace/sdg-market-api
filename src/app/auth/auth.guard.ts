import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot } from '@angular/router';
import { Observable, of } from 'rxjs';
import { map, catchError } from 'rxjs/operators';
import { ApiUserService } from '../services/api/api-user.service';

@Injectable()
export class AuthGuard implements CanActivate {

    /**
     *
     * @param router
     * @param userService
     */
    constructor(
        private router: Router,
        private userService: ApiUserService
    ) {
    }

    /**
     *
     * @param next
     * @param state
     */
    canActivate(next: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> | boolean {
        let role = next.data.expectedRole;

        return this.userService.getMe()
            .pipe(
                map(user => {
                    if (user !== null && user !== false && this.hasTheRole(user, role)) {
                        return true;
                    }
                    this.router.navigate(['login']);
                    return false;
                }),
                catchError((error) => {
                    this.router.navigate(['login']);
                    return Observable.throw(false);
                })
            );
    }

    /**
     * If no role add then its simple user, or else check if user has this role in his ROLE array from API response
     * @param role
     */
    private hasTheRole(user, role): boolean {
        console.log("TEST ROLE", user, role);
        if (!user) {
            return false;
        }

        if (role) {
            return user.roles.includes(role);
        }

        return true;
    }
}
