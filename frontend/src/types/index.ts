export interface Producto {
  id_producto: number;
  nombre: string;
  descripcion: string;
  precio: number;
  stock: number;
  imagen_url: string | null;
  id_categoria: number;
  id_disenador: number | null;
}

export interface Usuario {
  id_usuario: number;
  nombre: string;
  email: string;
  rol: string;
}

export interface ItemCarrito {
  id_producto: number;
  nombre: string;
  precio: number;
  imagen_url: string | null;
  cantidad: number;
}

export interface Disenador {
  id_disenador: number;
  nombre: string;
  biografia: string;
  web_url: string;
}
