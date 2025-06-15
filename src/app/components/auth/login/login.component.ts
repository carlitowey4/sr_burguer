import { Component } from '@angular/core';
import { Usuario } from '../../../interfaces/usuario';
import { AuthService } from '../../../services/auth.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-login',
  imports: [FormsModule, CommonModule, RouterModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {

  usuario: Usuario = {
    email: '',
    password: ''
  };

  mensaje: string = '';

  constructor(private authService: AuthService) {}

  login(): void {
    const datosEnvio = {
      email: this.usuario.email,
      contraseña: this.usuario.password
    };

    this.authService.login(datosEnvio).subscribe({
      next: (respuesta) => {
        localStorage.setItem('token', respuesta.token);
        alert('Inicio de sesión exitoso.');
        setTimeout(() => {
        window.location.href = '/home';
      }, 1000);
      },
      error: (err) => {
        this.mensaje = err.error?.message || 'Error al iniciar sesión.';
      }
    });
  }

}
