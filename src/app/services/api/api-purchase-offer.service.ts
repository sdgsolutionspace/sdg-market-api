import { Injectable } from '@angular/core';
import { BackendApiService } from './backend-api.service';
import { Observable } from 'rxjs';
import { PurchaseOffer } from 'src/app/interfaces/purchase-offer';

@Injectable({
  providedIn: 'root'
})
export class ApiPurchaseOfferService {

  constructor(private backendApi: BackendApiService) { }

  public getAll(projectId: number): Observable<Array<PurchaseOffer>> {
    return this.backendApi.get("purchase-offers", {
      "project": projectId
    });
  }

  public get(id: number): Observable<PurchaseOffer> {
    return this.backendApi.get(`purchase-offers/${id}`);
  }

  public update(id: number, data: PurchaseOffer): Observable<PurchaseOffer> {
    return this.backendApi.update(`purchase-offers/${id}`, data);
  }

  public create(data: PurchaseOffer): Observable<PurchaseOffer> {
    return this.backendApi.post(`purchase-offers`, data);
  }

  public delete(id: number): Observable<any> {
    return this.backendApi.delete(`purchase-offers/${id}`);
  }
}
