import { Component, Input } from '@angular/core';
import { Producto } from '../../../interfaces/producto';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { CestaService } from '../../../services/cesta.service';
import { AuthService } from '../../../services/auth.service';
import { FormsModule } from '@angular/forms';


@Component({
  selector: 'app-producto',
  imports: [CommonModule, FormsModule],
  templateUrl: './producto.component.html',
  styleUrl: './producto.component.css'
})
export class ProductoComponent {

  @Input() producto!: Producto;

  cantidad: number = 1;

  constructor(
    private cestaService: CestaService,
    private authService: AuthService,
    private router: Router
  ) {}

  productoDentro = false;

  meterACesta() {
    if (!this.authService.estaAutenticado()) {
      this.router.navigate(['/login']);
    } else {
      this.cestaService.añadirProducto(this.producto, this.cantidad);
      this.productoDentro = true;

      setTimeout(() => {
      this.productoDentro = false;
      }, 2000); 
    }
  }
}
