import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { CestaService } from '../../services/cesta.service';
import { AuthService } from '../../services/auth.service';
import { PedidoService } from '../../services/pedido.service';
import { CommonModule} from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-pedido',
  imports: [FormsModule, RouterModule, CommonModule],
  templateUrl: './pedido.component.html',
  styleUrl: './pedido.component.css'
})
export class PedidoComponent implements OnInit{

  cesta: any[] = [];
  direccion: string = '';
  metodoPago: string = 'efectivo';
  mensaje: string = '';

  constructor(
    private cestaService: CestaService,
    private authService: AuthService,
    private pedidoService: PedidoService,
    private router: Router
  ) {}

  ngOnInit(): void {
    if (!this.authService.estaAutenticado()) {
      this.router.navigate(['/login']);
      return;
    }

    const direccionToken = this.authService.obtenerDireccionDelToken();
    if (direccionToken) {
    this.direccion = direccionToken;
    }

    this.cesta = this.cestaService.obtenerCesta();
    if (this.cesta.length === 0) {
      this.router.navigate(['/menu']);
    }
  }

  total(): number {
    return this.cesta.reduce((acc, item) =>
      acc + (Number(item.precio) * Number(item.cantidad)), 0
    );
  }

  enviarPedido() {

    if (!this.direccion.trim()) {
    this.mensaje = '❌ La dirección de envío es obligatoria.';
    return;
    }

    const token = this.authService.obtenerToken();
    
    if (!token) {
    this.router.navigate(['/login']);
    return;
    }

    const pedido = {
      direccion_envio: this.direccion,
      metodo_pago: this.metodoPago,
      total: this.total(),
      productos: this.cesta
    };

    this.pedidoService.realizarPedido(pedido, token).subscribe({
      next: () => {
        this.mensaje = '✅ Pedido realizado con éxito. Serás redirigido en unos segundos...';
        this.cestaService.vaciarCesta();
        setTimeout(() => {
        this.router.navigate(['/']);
      }, 3000);
      },
      error: () => {
        this.mensaje = '❌ Error al realizar el pedido. Inténtalo más tarde.';
      }
    });
  }
}
