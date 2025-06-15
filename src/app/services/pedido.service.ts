import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class PedidoService {

  private apiUrl = 'http://vps-cc21a4cd.vps.ovh.net/mi_proyecto/backend/pedidos';

  constructor(private http: HttpClient) {}

  realizarPedido(pedido: any, token: string): Observable<any> {
    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    });

    return this.http.post(`${this.apiUrl}/realizar_pedido.php`, pedido, { headers });
  }

}
