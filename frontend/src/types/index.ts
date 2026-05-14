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

export interface Categoria {
  id_categoria: number;
  nombre: string;
  descripcion: string;
}

export interface CarritoResumen {
  id_carrito: number;
  id_usuario: number;
  cliente: string;
  email: string;
  fecha_creacion: string;
  estado: string;
  total_items: number;
}

export interface CartItem {
  id_producto: number;
  nombre: string;
  precio: number;
  imagen_url: string | null;
  cantidad: number;
}

export interface Pedido {
  id_pedido: number;
  id_usuario: number;
  cliente: string;
  fecha_pedido: string;
  total: number;
  estado: string;
}

export interface PedidoDetalle {
  id_detalle: number;
  id_producto: number;
  nombre: string;
  cantidad: number;
  precio_unitario: number;
}
