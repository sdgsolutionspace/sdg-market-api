import { Component, OnInit } from '@angular/core';
import { GitProjectApiService } from '../services/api/git-project-api.service';
import { GitProject } from '../interfaces/git-project';
import { ToastrService } from 'ngx-toastr';
import 'bootbox';
@Component({
  selector: 'app-repositories',
  templateUrl: './repositories.component.html',
  styleUrls: ['./repositories.component.scss']
})
export class RepositoriesComponent implements OnInit {

  title = 'GitHub Trading';

  public projects: Array<GitProject> = null;

  constructor(private projectsApi: GitProjectApiService, public toastr: ToastrService) {
  }

  private removeProjectFromList(projectId: number) {
    this.projects = this.projects.filter((el) => {
      return el.id !== projectId
    });
  }
  public deleteItem(projectId: number) {
    let self = this;
    bootbox.confirm("Do you really want to remove this project ?", (confirmation) => {
      if (confirmation) {
        this.removeProjectFromList(projectId);
        this.projectsApi.delete(projectId).toPromise().then(data => {
          self.toastr.success("The project was removed successfully.");
        }).catch(err => {
          self.toastr.error("This project can't be removed.");
        });
      }
    });
  }

  public ngOnInit() {
    this.projectsApi.getAll().subscribe((projects) => {
      this.projects = projects;
    });
  }

}
