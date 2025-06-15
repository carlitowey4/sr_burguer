import { Component, Input } from '@angular/core';
import { Producto } from '../../../interfaces/producto';
import { CommonModule } from '@angular/common';
import { ProductoComponent } from '../producto/producto.component';

@Component({
  selector: 'app-lista-productos',
  imports: [CommonModule, ProductoComponent],
  templateUrl: './lista-productos.component.html',
  styleUrl: './lista-productos.component.css'
})
export class ListaProductosComponent {
  @Input() productos: Producto[] = [];
  @Input() tipo!: string;

  isOpen: boolean = false;

  toggle() {
    this.isOpen = !this.isOpen;
  }

  get productosFiltrados(): Producto[] {
    return this.productos.filter(p => p.tipo === this.tipo && p.estado === 'disponible');
  }

}
