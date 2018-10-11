import { TestBed } from '@angular/core/testing';

import { GitProjectApiService } from './git-project-api.service';

describe('GitProjectApiService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: GitProjectApiService = TestBed.get(GitProjectApiService);
    expect(service).toBeTruthy();
  });
});
