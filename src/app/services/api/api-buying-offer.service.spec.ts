import { TestBed } from '@angular/core/testing';

import { ApiPurchaseOfferService } from './api-purchase-offer.service';

describe('ApiPurchaseOfferService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ApiPurchaseOfferService = TestBed.get(ApiPurchaseOfferService);
    expect(service).toBeTruthy();
  });
});
