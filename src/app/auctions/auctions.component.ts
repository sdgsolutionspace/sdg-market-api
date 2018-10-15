import { Component, OnInit } from '@angular/core';
import { GitProjectApiService } from '../services/api/git-project-api.service';
import { GitProject } from '../interfaces/git-project';

@Component({
  selector: 'app-auctions',
  templateUrl: './auctions.component.html',
  styleUrls: ['./auctions.component.scss']
})
export class AuctionsComponent implements OnInit {

  title = 'GitHub Trading';
  public projects: Array<GitProject> = null;

  constructor(private projectsApi: GitProjectApiService) {
  }

  ngOnInit() {
    this.projectsApi.getAll().subscribe((projects) => {
      this.projects = projects;
    });
  }

}
