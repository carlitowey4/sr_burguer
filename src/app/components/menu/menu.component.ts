import { Component } from '@angular/core';
import { ProductoService } from '../../services/producto.service';
import { Producto } from '../../interfaces/producto';
import { CommonModule } from '@angular/common';
import { ListaProductosComponent } from '../products/lista-productos/lista-productos.component';


@Component({
  selector: 'app-menu',
  imports: [CommonModule, ListaProductosComponent, ],
  templateUrl: './menu.component.html',
  styleUrl: './menu.component.css'
})
export class MenuComponent {
  productos: Producto[] = [];
  tipos: string[] = ['entrante', 'patata', 'ensalada', 'hamburguesa', 'pizza', 'bebida', 'postre'];

  constructor(private productoService: ProductoService) {}

  ngOnInit(): void {
    this.productoService.getProductos().subscribe(data => {
      console.log('Productos recibidos:', data);
      this.productos = data;
    });
  }

}
