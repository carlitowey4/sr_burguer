import { Component } from '@angular/core';
import { Usuario } from '../../../interfaces/usuario';
import { AuthService } from '../../../services/auth.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-registro',
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './registro.component.html',
  styleUrl: './registro.component.css'
})
export class RegistroComponent {

  usuario: Usuario = {
    nombre: '',
    email: '',
    password: '',
    direccion: ''
  };

  mensaje: string = '';

  constructor(private authService: AuthService) {}

  registrar(): void {
    const datosEnvio = {
      nombre: this.usuario.nombre,
      email: this.usuario.email,
      contraseña: this.usuario.password,
      direccion: this.usuario.direccion
    };

    this.authService.registrar(datosEnvio).subscribe({
      next: () => {
        this.usuario = { nombre: '', email: '', password: '', direccion: '' };
        alert('Usuario registrado con éxito.');
        setTimeout(() => {
        window.location.href = '/home';
      }, 1000);
      },
      error: (err) => {
        this.mensaje = err.error?.message || 'Error al registrar usuario.';
      }
    });
  }
}
