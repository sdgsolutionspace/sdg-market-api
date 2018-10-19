import { TestBed } from '@angular/core/testing';

import { ApiUserService } from './api-user.service';

describe('ApiUserService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ApiUserService = TestBed.get(ApiUserService);
    expect(service).toBeTruthy();
  });
});
