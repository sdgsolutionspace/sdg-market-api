import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EditRepositoryComponent } from './edit-repository.component';

describe('EditRepositoryComponent', () => {
  let component: EditRepositoryComponent;
  let fixture: ComponentFixture<EditRepositoryComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EditRepositoryComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EditRepositoryComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
