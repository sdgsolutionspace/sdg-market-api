import { TestBed } from '@angular/core/testing';

import { ApiSellOfferService } from './api-sell-offer.service';

describe('ApiSellOfferService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ApiSellOfferService = TestBed.get(ApiSellOfferService);
    expect(service).toBeTruthy();
  });
});
