import { useEffect, useState } from 'react';
import AdminRoute from '../components/AdminRoute';
import apiClient from '../api/client';
import type { Pedido, PedidoDetalle } from '../types';

const statusOptions = ['pendiente', 'pagado', 'enviado', 'cancelado', 'completado'];

const AdminPedidos = () => {
  const [orders, setOrders] = useState<Pedido[]>([]);
  const [details, setDetails] = useState<PedidoDetalle[]>([]);
  const [selectedOrder, setSelectedOrder] = useState<number | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const loadOrders = async () => {
    try {
      const res = await apiClient.get<Pedido[]>('/admin/pedidos');
      setOrders(res.data);
      setError('');
    } catch (err: any) {
      console.error('Error cargando pedidos:', err);
      setError(err.response?.data?.error || 'No se pudieron cargar los pedidos.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadOrders();
  }, []);

  const loadOrderDetails = async (id_pedido: number) => {
    try {
      const res = await apiClient.get<PedidoDetalle[]>(`/admin/pedidos/${id_pedido}/detalles`);
      setSelectedOrder(id_pedido);
      setDetails(res.data);
      setError('');
    } catch (err: any) {
      console.error('Error cargando detalles de pedido:', err);
      setError(err.response?.data?.error || 'No se pudieron cargar los detalles.');
    }
  };

  const updateStatus = async (id_pedido: number, estado: string) => {
    try {
      await apiClient.put(`/admin/pedidos/${id_pedido}`, { estado });
      setOrders((prev) => prev.map((order) => order.id_pedido === id_pedido ? { ...order, estado } : order));
      setError('');
    } catch (err: any) {
      console.error('Error actualizando pedido:', err);
      setError(err.response?.data?.error || 'No se pudo actualizar el pedido.');
    }
  };

  return (
    <AdminRoute>
      <main className="pt-[120px] px-[50px] pb-[50px] bg-gray-50">
        <header className="flex justify-between items-center mb-[50px] border-b-2 border-black pb-5">
          <h2 className="m-0 text-[28px] font-black tracking-tight">Pedidos</h2>
          <p className="text-gray-600 text-sm">Gestiona el estado de los pedidos y revisa sus detalles.</p>
        </header>

        {error && <p className="text-red-500 text-sm mb-2.5">{error}</p>}

        {loading ? (
          <p className="text-gray-500">Cargando pedidos...</p>
        ) : (
          <>
            <div className="border border-gray-200">
              <div className="flex bg-black text-white font-bold text-xs uppercase p-2.5 border-b border-gray-200">
                <span className="flex-1">ID</span>
                <span className="flex-1">Cliente</span>
                <span className="flex-1">Fecha</span>
                <span className="flex-1">Total</span>
                <span className="flex-1">Estado</span>
                <span className="flex-1">Acciones</span>
              </div>
              {orders.map((order) => (
                <div key={order.id_pedido} className="flex p-2.5 border-b border-gray-100 items-center text-sm">
                  <span className="flex-1">{order.id_pedido}</span>
                  <span className="flex-1">{order.cliente}</span>
                  <span className="flex-1">{new Date(order.fecha_pedido).toLocaleString()}</span>
                  <span className="flex-1">{order.total.toFixed(2)}€</span>
                  <span className="flex-1">
                    <select value={order.estado} onChange={(e) => updateStatus(order.id_pedido, e.target.value)} className="p-1.25 border border-gray-300 rounded text-xs">
                      {statusOptions.map((status) => (
                        <option key={status} value={status}>{status}</option>
                      ))}
                    </select>
                  </span>
                  <span className="flex-1">
                    <button onClick={() => loadOrderDetails(order.id_pedido)} className="px-2.5 py-1.25 bg-gray-600 text-white text-xs font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80">
                      Ver detalles
                    </button>
                  </span>
                </div>
              ))}
            </div>

            {selectedOrder !== null && (
              <section className="mt-5">
                <h3 className="text-lg font-bold mb-2.5">Detalles del pedido #{selectedOrder}</h3>
                {details.length === 0 ? (
                  <p className="text-gray-500">Este pedido no tiene detalles.</p>
                ) : (
                  <div className="border border-gray-200">
                    <div className="flex bg-black text-white font-bold text-xs uppercase p-2.5 border-b border-gray-200">
                      <span className="flex-1">ID producto</span>
                      <span className="flex-1">Nombre</span>
                      <span className="flex-1">Cantidad</span>
                      <span className="flex-1">Precio unitario</span>
                      <span className="flex-1">Total</span>
                    </div>
                    {details.map((item) => (
                      <div key={item.id_detalle} className="flex p-2.5 border-b border-gray-100 items-center text-sm">
                        <span className="flex-1">{item.id_producto}</span>
                        <span className="flex-1">{item.nombre}</span>
                        <span className="flex-1">{item.cantidad}</span>
                        <span className="flex-1">{item.precio_unitario.toFixed(2)}€</span>
                        <span className="flex-1">{(item.precio_unitario * item.cantidad).toFixed(2)}€</span>
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

export default AdminPedidos;
