import { useContext, useEffect, useState } from 'react';
import { Navigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';
import apiClient from '../api/client';
import type { Usuario, Producto, Disenador, Categoria, Pedido } from '../types';

const tabs = ['usuarios', 'productos', 'disenadores', 'categorias', 'pedidos'] as const;
type Tab = (typeof tabs)[number];

const statusOptions = ['pendiente', 'pagado', 'enviado', 'cancelado', 'completado'];

const Admin = () => {
  const { user, loading } = useContext(AuthContext);
  const [tab, setTab] = useState<Tab>('usuarios');
  const [loadingData, setLoadingData] = useState(true);
  const [error, setError] = useState('');
  const [refreshKey, setRefreshKey] = useState(0);

  const [users, setUsers] = useState<Usuario[]>([]);
  const [products, setProducts] = useState<Producto[]>([]);
  const [designers, setDesigners] = useState<Disenador[]>([]);
  const [categories, setCategories] = useState<Categoria[]>([]);
  const [orders, setOrders] = useState<Pedido[]>([]);

  const [productForm, setProductForm] = useState({
    nombre: '',
    descripcion: '',
    precio: 0,
    stock: 0,
    imagen_url: '',
    id_categoria: '',
    id_disenador: '',
  });

  const [designerForm, setDesignerForm] = useState({ nombre: '', biografia: '', web_url: '' });
  const [categoryForm, setCategoryForm] = useState({ nombre: '', descripcion: '' });

  useEffect(() => {
    if (!user || user.rol !== 'admin') return;

    const load = async () => {
      setLoadingData(true);
      setError('');

      try {
        if (tab === 'usuarios') {
          const res = await apiClient.get<Usuario[]>('/admin/usuarios');
          setUsers(res.data);
        }
        if (tab === 'productos') {
          const [prodRes, catRes, disRes] = await Promise.all([
            apiClient.get<Producto[]>('/admin/productos'),
            apiClient.get<Categoria[]>('/admin/categorias'),
            apiClient.get<Disenador[]>('/admin/disenadores'),
          ]);
          setProducts(prodRes.data);
          setCategories(catRes.data);
          setDesigners(disRes.data);
        }
        if (tab === 'disenadores') {
          const res = await apiClient.get<Disenador[]>('/admin/disenadores');
          setDesigners(res.data);
        }
        if (tab === 'categorias') {
          const res = await apiClient.get<Categoria[]>('/admin/categorias');
          setCategories(res.data);
        }
        if (tab === 'pedidos') {
          const res = await apiClient.get<Pedido[]>('/admin/pedidos');
          setOrders(res.data);
        }
      } catch (err: any) {
        console.error('Error cargando panel admin:', err);
        setError(err.response?.data?.error || 'Error cargando información');
      } finally {
        setLoadingData(false);
      }
    };

    load();
  }, [tab, user, refreshKey]);

  if (loading) {
    return <p className="text-gray-500 p-5">Cargando...</p>;
  }

  if (!user) {
    return <Navigate to="/login" replace />;
  }

  if (user.rol !== 'admin') {
    return <Navigate to="/" replace />;
  }

  const reloadTab = () => setTab((current) => current);

  const handleDelete = async (endpoint: string, id: number | string) => {
    try {
      await apiClient.delete(`/admin/${endpoint}/${id}`);
      setError('');
      setRefreshKey((value) => value + 1);
    } catch (err: any) {
      console.error('Error eliminando elemento admin:', err);
      setError(err.response?.data?.error || 'Error eliminando elemento');
    }
  };

  const handleStatusUpdate = async (orderId: number, estado: string) => {
    try {
      await apiClient.put(`/admin/pedidos/${orderId}`, { estado });
      setOrders((prev) => prev.map((order) => order.id_pedido === orderId ? { ...order, estado } : order));
      setError('');
    } catch (err: any) {
      console.error('Error actualizando pedido:', err);
      setError(err.response?.data?.error || 'Error actualizando pedido');
    }
  };

  const handleCreateProduct = async () => {
    try {
      await apiClient.post('/admin/productos', {
        ...productForm,
        id_categoria: productForm.id_categoria || null,
        id_disenador: productForm.id_disenador || null,
      });
      setProductForm({ nombre: '', descripcion: '', precio: 0, stock: 0, imagen_url: '', id_categoria: '', id_disenador: '' });
      setError('');
      const res = await apiClient.get<Producto[]>('/admin/productos');
      setProducts(res.data);
    } catch (err: any) {
      console.error('Error creando producto:', err);
      setError(err.response?.data?.error || 'Error creando producto');
    }
  };

  const handleCreateDesigner = async () => {
    try {
      await apiClient.post('/admin/disenadores', designerForm);
      setDesignerForm({ nombre: '', biografia: '', web_url: '' });
      setError('');
      const res = await apiClient.get<Disenador[]>('/admin/disenadores');
      setDesigners(res.data);
    } catch (err: any) {
      console.error('Error creando diseñador:', err);
      setError(err.response?.data?.error || 'Error creando diseñador');
    }
  };

  const handleCreateCategory = async () => {
    try {
      await apiClient.post('/admin/categorias', categoryForm);
      setCategoryForm({ nombre: '', descripcion: '' });
      setError('');
      const res = await apiClient.get<Categoria[]>('/admin/categorias');
      setCategories(res.data);
    } catch (err: any) {
      console.error('Error creando categoría:', err);
      setError(err.response?.data?.error || 'Error creando categoría');
    }
  };

  const renderTabButtons = () => (
    <div className="flex gap-1 mb-5">
      {tabs.map((tabName) => (
        <button
          key={tabName}
          className={`px-4 py-2 text-xs font-bold uppercase border-none cursor-pointer transition-colors ${tab === tabName ? 'bg-black text-white' : 'bg-gray-200 text-black hover:bg-gray-300'}`}
          onClick={() => setTab(tabName)}
        >
          {tabName.toUpperCase()}
        </button>
      ))}
    </div>
  );

  return (
    <main className="pt-[120px] px-[50px] pb-[50px] bg-gray-50">
      <header className="flex justify-between items-center mb-[50px] border-b-2 border-black pb-5">
        <h2 className="m-0 text-[28px] font-black tracking-tight">Panel de administración</h2>
        <p className="text-gray-600 text-sm">Bienvenido, {user.nombre}. Gestiona aquí tu base de datos.</p>
      </header>

      {renderTabButtons()}
      {error && <p className="text-red-500 text-sm mb-2.5">{error}</p>}

      {loadingData ? (
        <p className="text-gray-500">Cargando datos...</p>
      ) : (
        <section>
          {tab === 'usuarios' && (
            <>
              <h3 className="text-lg font-bold mb-3.75">Usuarios</h3>
              <div className="border border-gray-200">
                <div className="flex bg-black text-white font-bold text-xs uppercase p-2.5 border-b border-gray-200">
                  <span className="flex-1">ID</span>
                  <span className="flex-1">Nombre</span>
                  <span className="flex-1">Email</span>
                  <span className="flex-1">Rol</span>
                  <span className="flex-1">Acciones</span>
                </div>
                {users.map((usuario) => (
                  <div key={usuario.id_usuario} className="flex p-2.5 border-b border-gray-100 items-center text-sm">
                    <span className="flex-1">{usuario.id_usuario}</span>
                    <span className="flex-1">{usuario.nombre}</span>
                    <span className="flex-1">{usuario.email}</span>
                    <span className="flex-1">{usuario.rol}</span>
                    <span className="flex-1">
                      <button onClick={() => handleDelete('usuarios', usuario.id_usuario)} className="px-2.5 py-1.25 bg-red-600 text-white text-xs font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80">
                        Eliminar
                      </button>
                    </span>
                  </div>
                ))}
              </div>
            </>
          )}

          {tab === 'productos' && (
            <>
              <h3 className="text-lg font-bold mb-3.75">Productos</h3>
              <div className="bg-white p-5 border border-gray-200 mb-5">
                <h4 className="text-base font-bold mb-2.5">Crear producto</h4>
                <div className="flex gap-2.5 mb-2.5">
                  <input value={productForm.nombre} placeholder="Nombre" onChange={(e) => setProductForm((f) => ({ ...f, nombre: e.target.value }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm" />
                  <input value={productForm.precio} type="number" min="0" step="0.01" placeholder="Precio" onChange={(e) => setProductForm((f) => ({ ...f, precio: Number(e.target.value) }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm" />
                  <input value={productForm.stock} type="number" min="0" placeholder="Stock" onChange={(e) => setProductForm((f) => ({ ...f, stock: Number(e.target.value) }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm" />
                </div>
                <div className="flex gap-2.5 mb-2.5">
                  <input value={productForm.imagen_url} placeholder="Imagen URL" onChange={(e) => setProductForm((f) => ({ ...f, imagen_url: e.target.value }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm" />
                  <select value={productForm.id_categoria} onChange={(e) => setProductForm((f) => ({ ...f, id_categoria: e.target.value }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm">
                    <option value="">Categoría</option>
                    {categories.map((cat) => (
                      <option key={cat.id_categoria} value={cat.id_categoria}>{cat.nombre}</option>
                    ))}
                  </select>
                  <select value={productForm.id_disenador} onChange={(e) => setProductForm((f) => ({ ...f, id_disenador: e.target.value }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm">
                    <option value="">Diseñador</option>
                    {designers.map((dis) => (
                      <option key={dis.id_disenador} value={dis.id_disenador}>{dis.nombre}</option>
                    ))}
                  </select>
                </div>
                <textarea value={productForm.descripcion} placeholder="Descripción" onChange={(e) => setProductForm((f) => ({ ...f, descripcion: e.target.value }))} className="w-full p-2.5 border border-gray-300 rounded text-sm mb-2.5" rows={3} />
                <button onClick={handleCreateProduct} className="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80">Crear producto</button>
              </div>

              <div className="border border-gray-200">
                <div className="flex bg-black text-white font-bold text-xs uppercase p-2.5 border-b border-gray-200">
                  <span className="flex-1">ID</span>
                  <span className="flex-1">Nombre</span>
                  <span className="flex-1">Precio</span>
                  <span className="flex-1">Stock</span>
                  <span className="flex-1">Categoría</span>
                  <span className="flex-1">Diseñador</span>
                  <span className="flex-1">Acciones</span>
                </div>
                {products.map((producto) => (
                  <div key={producto.id_producto} className="flex p-2.5 border-b border-gray-100 items-center text-sm">
                    <span className="flex-1">{producto.id_producto}</span>
                    <span className="flex-1">{producto.nombre}</span>
                    <span className="flex-1">{producto.precio.toFixed(2)}€</span>
                    <span className="flex-1">{producto.stock}</span>
                    <span className="flex-1">{producto.id_categoria ?? '-'}</span>
                    <span className="flex-1">{producto.id_disenador ?? '-'}</span>
                    <span className="flex-1">
                      <button onClick={() => handleDelete('productos', producto.id_producto)} className="px-2.5 py-1.25 bg-red-600 text-white text-xs font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80">Eliminar</button>
                    </span>
                  </div>
                ))}
              </div>
            </>
          )}

          {tab === 'disenadores' && (
            <>
              <h3 className="text-lg font-bold mb-3.75">Diseñadores</h3>
              <div className="bg-white p-5 border border-gray-200 mb-5">
                <h4 className="text-base font-bold mb-2.5">Crear diseñador</h4>
                <div className="flex gap-2.5 mb-2.5">
                  <input value={designerForm.nombre} placeholder="Nombre" onChange={(e) => setDesignerForm((f) => ({ ...f, nombre: e.target.value }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm" />
                  <input value={designerForm.web_url} placeholder="Web URL" onChange={(e) => setDesignerForm((f) => ({ ...f, web_url: e.target.value }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm" />
                </div>
                <textarea value={designerForm.biografia} placeholder="Biografía" onChange={(e) => setDesignerForm((f) => ({ ...f, biografia: e.target.value }))} className="w-full p-2.5 border border-gray-300 rounded text-sm mb-2.5" rows={3} />
                <button onClick={handleCreateDesigner} className="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80">Crear diseñador</button>
              </div>

              <div className="border border-gray-200">
                <div className="flex bg-black text-white font-bold text-xs uppercase p-2.5 border-b border-gray-200">
                  <span className="flex-1">ID</span>
                  <span className="flex-1">Nombre</span>
                  <span className="flex-1">Web</span>
                  <span className="flex-1">Acciones</span>
                </div>
                {designers.map((dis) => (
                  <div key={dis.id_disenador} className="flex p-2.5 border-b border-gray-100 items-center text-sm">
                    <span className="flex-1">{dis.id_disenador}</span>
                    <span className="flex-1">{dis.nombre}</span>
                    <span className="flex-1">{dis.web_url || '-'}</span>
                    <span className="flex-1">
                      <button onClick={() => handleDelete('disenadores', dis.id_disenador)} className="px-2.5 py-1.25 bg-red-600 text-white text-xs font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80">Eliminar</button>
                    </span>
                  </div>
                ))}
              </div>
            </>
          )}

          {tab === 'categorias' && (
            <>
              <h3 className="text-lg font-bold mb-3.75">Categorías</h3>
              <div className="bg-white p-5 border border-gray-200 mb-5">
                <h4 className="text-base font-bold mb-2.5">Crear categoría</h4>
                <input value={categoryForm.nombre} placeholder="Nombre" onChange={(e) => setCategoryForm((f) => ({ ...f, nombre: e.target.value }))} className="w-full p-2.5 border border-gray-300 rounded text-sm mb-2.5" />
                <textarea value={categoryForm.descripcion} placeholder="Descripción" onChange={(e) => setCategoryForm((f) => ({ ...f, descripcion: e.target.value }))} className="w-full p-2.5 border border-gray-300 rounded text-sm mb-2.5" rows={3} />
                <button onClick={handleCreateCategory} className="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80">Crear categoría</button>
              </div>

              <div className="border border-gray-200">
                <div className="flex bg-black text-white font-bold text-xs uppercase p-2.5 border-b border-gray-200">
                  <span className="flex-1">ID</span>
                  <span className="flex-1">Nombre</span>
                  <span className="flex-1">Descripción</span>
                  <span className="flex-1">Acciones</span>
                </div>
                {categories.map((cat) => (
                  <div key={cat.id_categoria} className="flex p-2.5 border-b border-gray-100 items-center text-sm">
                    <span className="flex-1">{cat.id_categoria}</span>
                    <span className="flex-1">{cat.nombre}</span>
                    <span className="flex-1">{cat.descripcion}</span>
                    <span className="flex-1">
                      <button onClick={() => handleDelete('categorias', cat.id_categoria)} className="px-2.5 py-1.25 bg-red-600 text-white text-xs font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80">Eliminar</button>
                    </span>
                  </div>
                ))}
              </div>
            </>
          )}

          {tab === 'pedidos' && (
            <>
              <h3 className="text-lg font-bold mb-3.75">Pedidos</h3>
              <div className="border border-gray-200">
                <div className="flex bg-black text-white font-bold text-xs uppercase p-2.5 border-b border-gray-200">
                  <span className="flex-1">ID</span>
                  <span className="flex-1">Cliente</span>
                  <span className="flex-1">Fecha</span>
                  <span className="flex-1">Total</span>
                  <span className="flex-1">Estado</span>
                  <span className="flex-1">Actualizar</span>
                </div>
                {orders.map((pedido) => (
                  <div key={pedido.id_pedido} className="flex p-2.5 border-b border-gray-100 items-center text-sm">
                    <span className="flex-1">{pedido.id_pedido}</span>
                    <span className="flex-1">{pedido.cliente}</span>
                    <span className="flex-1">{new Date(pedido.fecha_pedido).toLocaleString()}</span>
                    <span className="flex-1">{pedido.total.toFixed(2)}€</span>
                    <span className="flex-1">{pedido.estado}</span>
                    <span className="flex-1">
                      <select value={pedido.estado} onChange={(e) => handleStatusUpdate(pedido.id_pedido, e.target.value)} className="p-1.25 border border-gray-300 rounded text-xs">
                        {statusOptions.map((status) => (
                          <option key={status} value={status}>{status}</option>
                        ))}
                      </select>
                    </span>
                  </div>
                ))}
              </div>
            </>
          )}
        </section>
      )}
    </main>
  );
};

export default Admin;
