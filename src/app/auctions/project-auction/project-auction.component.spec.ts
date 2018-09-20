import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ProjectAuctionComponent } from './project-auction.component';

describe('ProjectAuctionComponent', () => {
  let component: ProjectAuctionComponent;
  let fixture: ComponentFixture<ProjectAuctionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ProjectAuctionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ProjectAuctionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
