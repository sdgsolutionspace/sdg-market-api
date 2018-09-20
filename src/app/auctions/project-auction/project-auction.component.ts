import { Component, OnInit } from '@angular/core';

@Component( {
  selector: 'app-project-auction',
  templateUrl: './project-auction.component.html',
  styleUrls: [ './project-auction.component.scss' ]
} )
export class ProjectAuctionComponent implements OnInit {

  title = 'GitHub Trading';
  selectedTab = 1;

  constructor() {
  }

  ngOnInit() {
  }

}
