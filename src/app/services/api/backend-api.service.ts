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
  constructor(private http: HttpClient) { }

  private httpOptions(parameters?: object) {
    let headers = {
      'Content-Type': 'application/json',
    };

    let token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1MzkyNDI1MTIsImV4cCI6MTU0MTg3MDUxMiwicm9sZXMiOm51bGwsInVzZXJuYW1lIjoiZ2lsbGVzLWhlbW1lcmxlIn0.eVdiX96j-yd-TLuJYIVgNmLiIMwYjtdYDDZK6Muc-VbUslkf1nHgVCZhvPKTzeD9w8UTltw2QISDZwF83L-tJNT7qOlcMPDEw1HcFhu3xTl6T9DhlS7fJFwXD8WV4vn-z2SyNDhTqsV_Wn-US0tKrteOZFimYG5OxRC9WdL2R8j8w_pwMDpWcRqueYmBibsFXkNUEXYmJ4ugnrQ5ovIP4PRtyrv6pEMbk3QvxLaMsQa5asdhSwjA4ZjKv-cuzApXfmJcLKbfk9uACXx0RggNNvDqWAJ8IWCSoJddrH-pjREsP71G7nJMT_oY7nRt7T7REdRTsGB2L7VgJL8ou8ToCAK2JLYv7KgEQ3p8ibWJe6jknUwrF2xB9Q4pnJuichG91OorunOQwkmhPOxdRipAjRfWYJvzJxde0OSP3NconnxFqOgOkGlZ5kJ6kiC3Ng9u9aTpnSGTb3NZex-1YXw83vu7oxW1AdGdlZH6gJl5U-rJkYzRPgzs5rqHFcbrBvXTheJ9GBUn0SjArv55dtsKcy_UTaFELaU-Cp15Ul1bv5AFKF6jC4mEjODAgJzfUkEc1MJqfEIf5X6uWqUPROhsPqUy5XBMtCZEL1L6I5hpgWC7T1_0Wvp2WiQwJNtDJwEm15Oz-t9jt6ERB3PF6wXwI-7csBUs252ehXakKXNQcAs";
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
    let body = res;
    return body || {};
  }

  public get(apiUrl: string, parameters?: object): Observable<any> {

    return this.http.get(endpoint + apiUrl, this.httpOptions(parameters)).pipe(
      map(this.extractData));
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
