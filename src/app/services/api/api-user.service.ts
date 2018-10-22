import { Injectable } from '@angular/core';
import { BackendApiService } from './backend-api.service';
import { Observable } from 'rxjs';
import { User } from 'src/app/interfaces/user';

@Injectable({
  providedIn: 'root'
})
export class ApiUserService {

  constructor(private backendApi: BackendApiService) { }

  public getAll(): Observable<Array<User>> {
    return this.backendApi.get("users");
  }

  public get(username: string): Observable<User> {
    return this.backendApi.get(`users/${username}`);
  }

  public update(id: number, data: User): Observable<User> {
    return this.backendApi.update(`users/${id}`, data);
  }

  public create(data: User): Observable<User> {
    return this.backendApi.post(`users`, data);
  }

  public blacklist(id: number): Observable<any> {
    return this.backendApi.post(`users/${id}/blacklist`, {});
  }

  public getMe() {
    return this.backendApi.get('user/me');
  }
}
