import { Injectable } from '@angular/core';
import { BackendApiService } from './backend-api.service';
import { Observable } from 'rxjs';
import { GitProject } from 'src/app/interfaces/git-project';

@Injectable({
  providedIn: 'root'
})
export class GitProjectApiService {

  constructor(private backendApi: BackendApiService) { }

  public getAll(): Observable<Array<GitProject>> {
    return this.backendApi.get("git-projects");
  }

  public get(id: number): Observable<GitProject> {
    return this.backendApi.get(`git-projects/${id}`);
  }

  public update(id: number, data: GitProject): Observable<GitProject> {
    return this.backendApi.update(`git-projects/${id}`, data);
  }

  public create(data: GitProject): Observable<GitProject> {
    return this.backendApi.post(`git-projects`, data);
  }

  public delete(id: number): Observable<any> {
    return this.backendApi.delete(`git-projects/${id}`);
  }
}
