import { useEffect, useState } from 'react';
import AdminRoute from '../components/AdminRoute';
import apiClient from '../api/client';
import type { CarritoResumen, CartItem } from '../types';

const cartStates = ['activo', 'abandonado', 'convertido'];

const AdminCarritos = () => {
  const [carts, setCarts] = useState<CarritoResumen[]>([]);
  const [selectedCartItems, setSelectedCartItems] = useState<CartItem[]>([]);
  const [selectedCartId, setSelectedCartId] = useState<number | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const loadCarts = async () => {
    try {
      const res = await apiClient.get<CarritoResumen[]>('/admin/carritos');
      setCarts(res.data);
      setError('');
    } catch (err: any) {
      console.error('Error cargando carritos:', err);
      setError(err.response?.data?.error || 'No se pudieron cargar los carritos.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadCarts();
  }, []);

  const loadCartItems = async (id_carrito: number) => {
    try {
      const res = await apiClient.get<CartItem[]>(`/admin/carritos/${id_carrito}/items`);
      setSelectedCartId(id_carrito);
      setSelectedCartItems(res.data);
      setError('');
    } catch (err: any) {
      console.error('Error cargando items de carrito:', err);
      setError(err.response?.data?.error || 'No se pudieron cargar los items.');
    }
  };

  const updateCartStatus = async (id_carrito: number, estado: string) => {
    try {
      await apiClient.put(`/admin/carritos/${id_carrito}`, { estado });
      setCarts((prev) => prev.map((cart) => cart.id_carrito === id_carrito ? { ...cart, estado } : cart));
      setError('');
    } catch (err: any) {
      console.error('Error actualizando estado del carrito:', err);
      setError(err.response?.data?.error || 'No se pudo actualizar el estado.');
    }
  };

  return (
    <AdminRoute>
      <main className="pt-[120px] px-[50px] pb-[50px] bg-gray-50">
        <header className="flex justify-between items-center mb-[50px] border-b-2 border-black pb-5">
          <h2 className="m-0 text-[28px] font-black tracking-tight">Carritos</h2>
          <p className="text-gray-600 text-sm">Revisa los carritos de usuarios y su estado actual.</p>
        </header>

        {error && <p className="text-red-500 text-sm mb-2.5">{error}</p>}

        {loading ? (
          <p className="text-gray-500">Cargando carritos...</p>
        ) : (
          <>
            <div className="border border-gray-200">
              <div className="flex bg-black text-white font-bold text-xs uppercase p-2.5 border-b border-gray-200">
                <span className="flex-1">ID</span>
                <span className="flex-1">Usuario</span>
                <span className="flex-1">Email</span>
                <span className="flex-1">Items</span>
                <span className="flex-1">Estado</span>
                <span className="flex-1">Acciones</span>
              </div>
              {carts.map((cart) => (
                <div key={cart.id_carrito} className="flex p-2.5 border-b border-gray-100 items-center text-sm">
                  <span className="flex-1">{cart.id_carrito}</span>
                  <span className="flex-1">{cart.cliente}</span>
                  <span className="flex-1">{cart.email}</span>
                  <span className="flex-1">{cart.total_items}</span>
                  <span className="flex-1">
                    <select value={cart.estado} onChange={(e) => updateCartStatus(cart.id_carrito, e.target.value)} className="p-1.25 border border-gray-300 rounded text-xs">
                      {cartStates.map((estado) => (
                        <option key={estado} value={estado}>{estado}</option>
                      ))}
                    </select>
                  </span>
                  <span className="flex-1">
                    <button onClick={() => loadCartItems(cart.id_carrito)} className="px-2.5 py-1.25 bg-gray-600 text-white text-xs font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80">
                      Ver items
                    </button>
                  </span>
                </div>
              ))}
            </div>

            {selectedCartId !== null && (
              <section className="mt-5">
                <h3 className="text-lg font-bold mb-2.5">Items del carrito #{selectedCartId}</h3>
                {selectedCartItems.length === 0 ? (
                  <p className="text-gray-500">Este carrito no tiene items.</p>
                ) : (
                  <div className="border border-gray-200">
                    <div className="flex bg-black text-white font-bold text-xs uppercase p-2.5 border-b border-gray-200">
                      <span className="flex-1">ID</span>
                      <span className="flex-1">Producto</span>
                      <span className="flex-1">Cantidad</span>
                      <span className="flex-1">Precio</span>
                      <span className="flex-1">Total</span>
                    </div>
                    {selectedCartItems.map((item) => (
                      <div key={item.id_producto} className="flex p-2.5 border-b border-gray-100 items-center text-sm">
                        <span className="flex-1">{item.id_producto}</span>
                        <span className="flex-1">{item.nombre}</span>
                        <span className="flex-1">{item.cantidad}</span>
                        <span className="flex-1">{item.precio.toFixed(2)}€</span>
                        <span className="flex-1">{(item.precio * item.cantidad).toFixed(2)}€</span>
                      </div>
                    ))}
                  </div>
                )}
              </section>
            )}
          </>
        )}
      </main>
    </AdminRoute>
  );
};

export default AdminCarritos;
