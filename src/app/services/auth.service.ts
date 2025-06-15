import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Usuario } from '../interfaces/usuario';
import { jwtDecode } from 'jwt-decode';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private apiUrl = 'http://vps-cc21a4cd.vps.ovh.net/mi_proyecto/backend/auth';

  constructor(private http: HttpClient) {}

  registrar(usuario: any): Observable<any> {
    
    return this.http.post(`${this.apiUrl}/registro.php`, usuario);
  }

  login(usuario: { email: string, contraseña: string }): Observable<any> {
    return this.http.post(`${this.apiUrl}/login.php`, usuario);
  }

  guardarToken(token: string): void {
    localStorage.setItem('token', token);
  }

  obtenerToken(): string | null {
    return localStorage.getItem('token');
  }

  cerrarSesion(): void {
    localStorage.removeItem('token');
  }

  estaAutenticado(): boolean {
    return !!this.obtenerToken();
  }

  guardarUsuario(usuario: Usuario): void {
    localStorage.setItem('usuario', JSON.stringify(usuario));
  }

  obtenerUsuario(): Usuario | null {
    const usuarioStr = localStorage.getItem('usuario');
    return usuarioStr ? JSON.parse(usuarioStr) : null;
  }

  obtenerIdDelToken(): number | null {
  const token = this.obtenerToken();
  if (!token) return null;

  try {
    const decoded: any = jwtDecode(token);
    return decoded?.data?.id ?? null;
  } catch (error) {
    console.error('Error al decodificar el token', error);
    return null;
  }
  }

  obtenerDireccionDelToken(): string | null {
  const token = this.obtenerToken();
  if (!token) return null;

  try {
    const decoded: any = jwtDecode(token);
    return decoded?.data?.direccion ?? null;
  } catch (error) {
    console.error('Error al decodificar el token', error);
    return null;
  }
}
}
