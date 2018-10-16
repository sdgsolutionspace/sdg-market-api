import { Injectable } from '@angular/core';
import {BackendApiService} from "./backend-api.service";

@Injectable({
  providedIn: 'root'
})
export class UserService {

    constructor(private backendApi: BackendApiService) {}

    public getMe() {
      return this.backendApi.get('user/me');
    }
}
