import { Component, OnInit } from '@angular/core';
import { CestaService } from '../../services/cesta.service';
import { AuthService } from '../../services/auth.service';
import { Router, RouterModule } from '@angular/router';
import { CommonModule} from '@angular/common';

@Component({
  selector: 'app-cesta',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './cesta.component.html',
  styleUrls: ['./cesta.component.css']
})
export class CestaComponent implements OnInit {

  cesta: any[] = [];

  constructor(
    private cestaService: CestaService,
    private authService: AuthService,
    private router: Router
  ) {}

  ngOnInit(): void {
    if (!this.authService.estaAutenticado()) {
      this.router.navigate(['/login']);
      return;
    }
    this.cargarCesta();
  }

  cargarCesta() {
    this.cesta = this.cestaService.obtenerCesta();
  }

  eliminarProducto(id: number) {
    this.cestaService.eliminarProducto(id);
    this.cargarCesta();
  }

  vaciarCesta() {
    this.cestaService.vaciarCesta();
    this.cargarCesta();
  }

  total(): number {
    return this.cesta.reduce((acc, item) => 
      acc + (Number(item.precio) * Number(item.cantidad)), 0
    );
  }

  irAPedido() {
    this.router.navigate(['/pedido']);
  }

}
