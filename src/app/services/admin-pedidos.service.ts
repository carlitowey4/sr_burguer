import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AdminPedidosService {

  private apiUrl = 'http://vps-cc21a4cd.vps.ovh.net/mi_proyecto/backend/pedidos';

  constructor(private http: HttpClient) {}

  obtenerPedidosRecientes(): Observable<any> {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${localStorage.getItem('token')}`
    });
    return this.http.get(`${this.apiUrl}/pedidos_recientes.php`, { headers });
  }

  actualizarEstado(pedido_id: number, nuevo_estado: string): Observable<any> {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${localStorage.getItem('token')}`
    });
    return this.http.post(`${this.apiUrl}/estado_pedido.php`, {
      pedido_id,
      nuevo_estado
    }, { headers });
  }
}
