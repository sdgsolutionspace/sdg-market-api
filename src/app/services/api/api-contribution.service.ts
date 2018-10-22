import { Injectable } from '@angular/core';
import { BackendApiService } from './backend-api.service';
import { Observable } from 'rxjs';
import { Contribution } from 'src/app/interfaces/contribution';
import { GitProject } from 'src/app/interfaces/git-project';
import { User } from 'src/app/interfaces/user';

interface contributionSearch {
  project?: GitProject,
  user?: User
}

@Injectable({
  providedIn: 'root'
})
export class ApiContributionService {

  constructor(private backendApi: BackendApiService) { }

  public getAll(search: contributionSearch): Observable<Array<Contribution>> {
    return this.backendApi.get("contributions", {
      "project": search.project ? search.project.id : null,
      "user": search.user ? search.user.id : null
    });
  }

  public get(id: number): Observable<Contribution> {
    return this.backendApi.get(`contributions/${id}`);
  }

  public update(id: number, data: Contribution): Observable<Contribution> {
    return this.backendApi.update(`contributions/${id}`, data);
  }

  public create(data: Contribution): Observable<Contribution> {
    return this.backendApi.post(`contributions`, data);
  }

  public delete(id: number): Observable<any> {
    return this.backendApi.delete(`contributions/${id}`);
  }
}
