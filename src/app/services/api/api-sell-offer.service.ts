import { Injectable } from '@angular/core';
import { BackendApiService } from './backend-api.service';
import { Observable } from 'rxjs';
import { SellOffer } from 'src/app/interfaces/sell-offer';

@Injectable({
  providedIn: 'root'
})
export class ApiSellOfferService {

  constructor(private backendApi: BackendApiService) { }

  public getAll(projectId: number): Observable<Array<SellOffer>> {
    return this.backendApi.get("sell-offers", {
      "project": projectId
    });
  }

  public get(id: number): Observable<SellOffer> {
    return this.backendApi.get(`sell-offers/${id}`);
  }

  public update(id: number, data: SellOffer): Observable<SellOffer> {
    return this.backendApi.update(`sell-offers/${id}`, data);
  }

  public create(data: SellOffer): Observable<SellOffer> {
    return this.backendApi.post(`sell-offers`, data);
  }

  public delete(id: number): Observable<any> {
    return this.backendApi.delete(`sell-offers/${id}`);
  }
}
