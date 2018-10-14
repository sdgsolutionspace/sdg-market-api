import {Injectable} from '@angular/core';
import {HttpClient, HttpErrorResponse, HttpHeaders} from '@angular/common/http';
import {Observable, of } from 'rxjs';
import {map} from 'rxjs/operators';
import { catchError } from 'rxjs/operators';
import {environment} from 'src/environments/environment';

const endpoint = environment.baseAPIUrl;

@Injectable({
    providedIn: 'root'
})
export class BackendApiService {
    constructor(private http: HttpClient) {
    }

    private httpOptions() {
        let headers = {
            'Content-Type': 'application/json',
        };
        let token = localStorage.getItem(environment.localStorageJWT);
        headers["Authorization"] = `Bearer ${token}`;
        let httpHeaders = new HttpHeaders(headers);
        return {
            headers: httpHeaders
        };
    }

    private extractData(res: Response) {
        return res || {};
    }

    public get(apiUrl: string): Observable<any> {
        return this.http.get(endpoint + apiUrl, this.httpOptions())
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
