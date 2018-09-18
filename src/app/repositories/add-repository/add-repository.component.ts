import { Component, OnInit } from '@angular/core';

@Component( {
  selector: 'app-add-repository',
  templateUrl: './add-repository.component.html',
  styleUrls: [ './add-repository.component.scss' ]
} )
export class AddRepositoryComponent implements OnInit {

  title = 'GitHub Trading';

  constructor() {
  }

  ngOnInit() {
  }

}
