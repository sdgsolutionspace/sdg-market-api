import {Injectable} from '@angular/core';
import {ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot} from '@angular/router';
import {Observable, of} from 'rxjs';
import { map, catchError } from 'rxjs/operators';
import {UserService} from "../services/api/user.service";

@Injectable()
export class AuthGuard implements CanActivate {

    /**
     *
     * @param router
     * @param userService
     */
    constructor(
        private router: Router,
        private userService: UserService
    ) {
    }

    /**
     *
     * @param next
     * @param state
     */
    canActivate(next: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<boolean> {
        let role = next.data.expectedRole;

        return this.userService.getMe()
            .pipe(
                map(user => {
                    debugger;
                    if (user !== null && user !== false && this.hasTheRole(user, role)) {
                        return of(true);
                    }
                    this.router.navigate(['login']);
                    return of(false);
                }),
                catchError(error => {
                    this.router.navigate(['login']);
                    return of(false;
                })
            );
    }

    /**
     * If no role add then its simple user, or else chekc if user has this role in his ROLE array from API response
     * @param role
     */
    private hasTheRole(user, role): boolean {
        if(role){
            return user.roles.includes(role);
        }

        return true;
    }
}
