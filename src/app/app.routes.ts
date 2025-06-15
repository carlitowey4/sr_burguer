import { Routes } from '@angular/router';
import { HomeComponent } from './components/home/home.component';
import { MenuComponent } from './components/menu/menu.component';
import { RegistroComponent } from './components/auth/registro/registro.component';
import { LoginComponent } from './components/auth/login/login.component';
import { CestaComponent } from './components/cesta/cesta.component';
import { AuthGuard } from './guards/auth.guard';
import { PedidoComponent } from './components/pedido/pedido.component';
import { AdminPedidosComponent } from './components/admin-pedidos/admin-pedidos.component';
import { AdminGuard } from './guards/admin.guard';
import { PerfilComponent } from './components/perfil/perfil.component';


export const routes: Routes = [
    {path: '', component: HomeComponent, title: 'Home page'},
    {path: 'home', component: HomeComponent, title: 'Home page'},
    {path: 'menu', component: MenuComponent, title: 'Menu page'},
    {path: 'registro', component: RegistroComponent, title: 'Registro page'},
    {path: 'login', component: LoginComponent, title: 'Login page'},
    {path: 'cesta', component: CestaComponent, canActivate: [AuthGuard], title: 'Cesta page'},
    {path: 'pedido', component: PedidoComponent, canActivate: [AuthGuard], title: 'Pedido page'},
    {path: 'admin', component: AdminPedidosComponent, canActivate: [AdminGuard], title: 'Pedidos Admin page'},
    {path: 'perfil', component: PerfilComponent, canActivate: [AuthGuard], title: 'Perfil page'}
];
