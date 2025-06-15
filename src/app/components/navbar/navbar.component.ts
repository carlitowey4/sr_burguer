import { Component } from '@angular/core';
import { RouterModule, Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';


@Component({
  selector: 'app-navbar',
  imports: [RouterModule, CommonModule],
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.css'
})
export class NavbarComponent {

   constructor(
    public authService: AuthService, // Tiene que ser público para usarlo en el HTML con *ngIf
    private router: Router
  ) {}

  cerrarSesion() {
    this.authService.cerrarSesion(); // O el método que uses para limpiar el JWT/localStorage
    this.router.navigate(['/login']);
  }
}
