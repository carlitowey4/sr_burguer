import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AdminPedidosService } from '../../services/admin-pedidos.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-admin-pedidos',
  imports: [CommonModule, FormsModule],
  templateUrl: './admin-pedidos.component.html',
  styleUrl: './admin-pedidos.component.css'
})
export class AdminPedidosComponent implements OnInit{

  pedidos: any[] = [];

  constructor(
    private adminPedidoService: AdminPedidosService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.adminPedidoService.obtenerPedidosRecientes().subscribe({
      next: (pedidos) => {
        this.pedidos = pedidos.map((p: any) => ({ ...p, nuevo_estado: p.estado }));
      },
      error: () => this.router.navigate(['/'])
    });
  }

  cambiarEstado(pedido: any) {
    if (pedido.estado === pedido.nuevo_estado) return;

    this.adminPedidoService.actualizarEstado(pedido.id, pedido.nuevo_estado).subscribe({
      next: () => pedido.estado = pedido.nuevo_estado,
      error: (err) => console.error('Error al actualizar estado', err)
    });
  }
}
