import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AdminGuard implements CanActivate {

  constructor(private authService: AuthService, private router: Router) {}

  canActivate(): boolean {
    const idUsuario = this.authService.obtenerIdDelToken();

    if (idUsuario === 1) {
      return true;
    }

    this.router.navigate(['/']);
    return false;
  }
}