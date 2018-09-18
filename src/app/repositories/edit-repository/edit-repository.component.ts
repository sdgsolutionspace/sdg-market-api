import { Component, OnInit } from '@angular/core';

@Component( {
  selector: 'app-edit-repository',
  templateUrl: './edit-repository.component.html',
  styleUrls: [ './edit-repository.component.scss' ]
} )
export class EditRepositoryComponent implements OnInit {

  title = 'GitHub Trading';

  constructor() {
  }

  ngOnInit() {
  }

}
