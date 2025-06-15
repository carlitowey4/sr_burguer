import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Producto } from '../interfaces/producto';

@Injectable({
  providedIn: 'root'
})
export class ProductoService {

  private apiUrl = 'http://vps-cc21a4cd.vps.ovh.net/mi_proyecto/backend/getProductos.php';

  constructor(private http: HttpClient) {}

  getProductos() : Observable<Producto[]>{    
    return this.http.get<Producto[]>(this.apiUrl);
  }

}
