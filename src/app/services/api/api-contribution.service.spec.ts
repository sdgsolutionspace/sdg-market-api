import { TestBed } from '@angular/core/testing';

import { ApiContributionService } from './api-contribution.service';

describe('ApiContributionService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ApiContributionService = TestBed.get(ApiContributionService);
    expect(service).toBeTruthy();
  });
});
