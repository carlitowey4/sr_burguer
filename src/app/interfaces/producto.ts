export interface Producto {
    id: number;
    nombre: string;
    descripcion: string;
    precio: number | string;
    estado: 'disponible' | 'no_disponible';
    tipo: 'entrante' | 'patata' | 'ensalada' | 'hamburguesa' | 'pizza' | 'bebida' | 'postre';
    imagen: string;
}
