import { Component, OnInit } from '@angular/core';

@Component( {
  selector: 'app-repositories',
  templateUrl: './repositories.component.html',
  styleUrls: [ './repositories.component.scss' ]
} )
export class RepositoriesComponent implements OnInit {

  title = 'GitHub Trading';

  constructor() {
  }

  ngOnInit() {
  }

}
