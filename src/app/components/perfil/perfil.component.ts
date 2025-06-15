import { Component, OnInit } from '@angular/core';
import { PerfilService } from '../../services/perfil.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { UsuarioPerfil } from '../../interfaces/usuario-perfil';
import { CestaService } from '../../services/cesta.service';

@Component({
  selector: 'app-perfil',
  imports: [CommonModule, FormsModule],
  templateUrl: './perfil.component.html',
  styleUrl: './perfil.component.css'
})
export class PerfilComponent implements OnInit {
  usuario!: UsuarioPerfil;
  usuarioMod: any = {};
  modoEdicion = false;

  pedidos: any[] = [];
  pedidosEnProceso: any[] = [];

  mostrarDetalles: { [key: number]: boolean } = {};

  constructor(private perfilService: PerfilService, private cestaService: CestaService) {}

  ngOnInit() {
    this.perfilService.obtenerPerfil().subscribe(data => {
      this.usuario = data;
      this.usuarioMod = { nombre: data.nombre, direccion: data.direccion, password: '' };
    });

    this.cargarPedidos();
  }

  cargarPedidos() {
  this.perfilService.obtenerPedidos().subscribe(pedidos => {
    this.pedidos = pedidos;

    const ahora = new Date();
    const hace12Horas = new Date(ahora.getTime() - 12 * 60 * 60 * 1000);

    this.pedidosEnProceso = pedidos.filter((p: any) => {
      const fechaPedido = new Date(p.fecha);
      return p.estado !== 'entregado' && fechaPedido >= hace12Horas;
    });
  });
}

  guardarCambios() {
    const datosActualizados = {
      nombre: this.usuarioMod.nombre,
      direccion: this.usuarioMod.direccion,
      password: this.usuarioMod.password ? this.usuarioMod.password : undefined
    };

    this.perfilService.actualizarPerfil(datosActualizados).subscribe(() => {
      this.usuario.nombre = datosActualizados.nombre;
      this.usuario.direccion = datosActualizados.direccion;
      this.modoEdicion = false;
    });
  }

  repetirPedido(pedido: any) {
  if (!pedido.productos) return;

  for (const producto of pedido.productos) {
    this.cestaService.añadirProducto({
      id: producto.producto_id,
      nombre: producto.nombre,
      precio: producto.subtotal / producto.cantidad,
      descripcion: producto.descripcion || '',
      imagen: producto.imagen || '',        
      estado: producto.estado || 'activo',   
      tipo: producto.tipo || 'otro'          
    }, producto.cantidad);
  }

  alert('Pedido añadido a la cesta');
}
}

