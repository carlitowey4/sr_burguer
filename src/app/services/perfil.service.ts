import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { AuthService } from './auth.service';
import { UsuarioPerfil } from '../interfaces/usuario-perfil';

@Injectable({
  providedIn: 'root'
})
export class PerfilService {
  private apiUrl = 'http://vps-cc21a4cd.vps.ovh.net/mi_proyecto/backend/usuario';

  constructor(private http: HttpClient, private auth: AuthService) {}

  private getHeaders(): HttpHeaders {
    return new HttpHeaders({
      Authorization: `Bearer ${this.auth.obtenerToken()}`,
      'Content-Type': 'application/json'
    });
  }

  obtenerPerfil(): Observable<UsuarioPerfil> {
    return this.http.get<UsuarioPerfil>(`${this.apiUrl}/perfil.php`, { headers: this.getHeaders() });
  }

  actualizarPerfil(datos: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/actualizar_perfil.php`, datos, { headers: this.getHeaders() });
  }

  obtenerPedidos(): Observable<any[]> {
    return this.http.get<any[]>(`${this.apiUrl}/usuario_pedidos.php`, { headers: this.getHeaders() });
  }
}
