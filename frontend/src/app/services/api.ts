import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class Api {
  private baseUrl = 'http://localhost:8000';

  constructor(private http: HttpClient) {}

  getClients(query?: string): Observable<any[]> {
    let params = new HttpParams();

    if (query) params = params.set('q', query);
    return this.http.get<any[]>(`${this.baseUrl}/clients`, { params });
  }

  getInvoices(): Observable<any[]> {
    return this.http.get<any[]>(`${this.baseUrl}/invoices`);
  }
  createInvoices(invoiceData: any): Observable<any> {
    return this.http.post<any[]>(`${this.baseUrl}/invoices`, invoiceData);
  }

  importInvoices(formData: FormData): Observable<ImportResponse> {
    return this.http.post<ImportResponse>(`${this.baseUrl}/invoices/import`, formData);
  }
}
export interface ImportResponse {
  created: number;
  failed: number;
  errors: { row: number; reason: string }[];
}
