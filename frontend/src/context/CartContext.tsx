import { createContext, useState, useEffect, useContext, ReactNode } from 'react';
import type { ItemCarrito } from '../types';
import apiClient from '../api/client';
import { AuthContext } from './AuthContext';

export interface CartContextType {
  items: ItemCarrito[];
  totalItems: number;
  totalPrice: number;
  addToCart: (id_producto: number) => Promise<void>;
  removeFromCart: (id_producto: number) => Promise<void>;
  refreshCart: () => Promise<void>;
}

export const CartContext = createContext<CartContextType>({} as CartContextType);

export const CartProvider = ({ children }: { children: ReactNode }) => {
  const [items, setItems] = useState<ItemCarrito[]>([]);
  const { token } = useContext(AuthContext);

  const refreshCart = async () => {
    if (!token) {
      setItems([]);
      return;
    }
    try {
      const res = await apiClient.get<ItemCarrito[]>('/carrito');
      setItems(res.data);
    } catch (err) {
      console.error('Error fetching cart:', err);
    }
  };

  useEffect(() => {
    refreshCart();
  }, [token]);

  const addToCart = async (id_producto: number) => {
    if (!token) return;
    try {
      await apiClient.post('/carrito/agregar', { id_producto });
      await refreshCart();
    } catch (err) {
      console.error('Error adding to cart:', err);
    }
  };

  const removeFromCart = async (id_producto: number) => {
    if (!token) return;
    try {
      await apiClient.delete(`/carrito/eliminar/${id_producto}`);
      await refreshCart();
    } catch (err) {
      console.error('Error removing from cart:', err);
    }
  };

  const totalItems = items.reduce((acc, item) => acc + item.cantidad, 0);
  const totalPrice = items.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);

  return (
    <CartContext.Provider value={{ items, totalItems, totalPrice, addToCart, removeFromCart, refreshCart }}>
      {children}
    </CartContext.Provider>
  );
};
