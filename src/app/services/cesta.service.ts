import { Injectable } from '@angular/core';
import { Producto } from '../interfaces/producto';

@Injectable({
  providedIn: 'root'
})
export class CestaService {
  private cesta: (Producto & { cantidad: number })[] = [];

  obtenerCesta() {
    return this.cesta;
  }

  añadirProducto(producto: Producto, cantidad: number) {
    const index = this.cesta.findIndex(p => p.id === producto.id);
    if (index !== -1) {
      this.cesta[index].cantidad += cantidad;
    } else {
      this.cesta.push({ ...producto, cantidad });
    }
  }

  eliminarProducto(id: number) {
    this.cesta = this.cesta.filter(p => p.id !== id);
  }

  vaciarCesta() {
    this.cesta = [];
  }
}
