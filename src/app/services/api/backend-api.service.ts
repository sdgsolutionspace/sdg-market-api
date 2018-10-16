import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse, HttpParams } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { environment } from 'src/environments/environment';

const endpoint = environment.baseAPIUrl;

@Injectable({
  providedIn: 'root'
})
export class BackendApiService {
  constructor(private http: HttpClient) {
  }


  // public get(apiUrl: string, parameters?: object): Observable<any> {

  //   return this.http.get(endpoint + apiUrl, this.httpOptions(parameters)).pipe(
  //     map(this.extractData));
  // }

  private httpOptions(parameters?: object) {
    let headers = {
      'Content-Type': 'application/json',
    };
    let token = localStorage.getItem(environment.localStorageJWT);
    headers["Authorization"] = `Bearer ${token}`;
    let httpHeaders = new HttpHeaders(headers);
    let params = new HttpParams();
    if (parameters) {
      for (let parameter in parameters) {
        params.set(parameter, parameters[parameter]);
      }
    }
    return {
      headers: httpHeaders,
      params: params
    };
  }

  private extractData(res: Response) {
    return res || {};
  }

  public get(apiUrl: string, parameters?: object): Observable<any> {
    return this.http.get(endpoint + apiUrl, this.httpOptions(parameters))
      .pipe(
        map(this.extractData),
        catchError((err: HttpErrorResponse) => {
          console.log(err)
          return of(false);
        })
      );
  }

  public post(apiUrl: string, data: any): Observable<any> {
    return this.http.post<any>(endpoint + apiUrl, JSON.stringify(data), this.httpOptions()).pipe(
    );
  }

  public update(apiUrl: string, data: any): Observable<any> {
    return this.http.put(endpoint + apiUrl, JSON.stringify(data), this.httpOptions()).pipe(
    );
  }

  public delete(apiUrl: string): Observable<any> {
    return this.http.delete<any>(endpoint + apiUrl, this.httpOptions()).pipe(
    );
  }
}
