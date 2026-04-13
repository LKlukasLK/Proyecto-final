import { useState, useEffect, useContext } from 'react';
import apiClient from '../api/client';
import type { Producto, Usuario, Disenador } from '../types';
import { AuthContext } from '../context/AuthContext';

type TabType = 'productos' | 'usuarios' | 'disenadores' | 'pedidos';

interface PedidoAdmin {
  id_pedido: number;
  total: number;
  estado: string;
  fecha_pedido: string;
  cliente: string;
  email: string;
}

const AdminPanel = () => {
  const { token } = useContext(AuthContext);
  const [activeTab, setActiveTab] = useState<TabType>('productos');
  const [loading, setLoading] = useState(false);

  // Data States
  const [productos, setProductos] = useState<Producto[]>([]);
  const [usuarios, setUsuarios] = useState<Usuario[]>([]);
  const [disenadores, setDisenadores] = useState<Disenador[]>([]);
  const [pedidos, setPedidos] = useState<PedidoAdmin[]>([]);

  // Modals
  const [isModalProdOpen, setIsModalProdOpen] = useState(false);
  const [isModalDisOpen, setIsModalDisOpen] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);

  const [formProd, setFormProd] = useState({ nombre: '', descripcion: '', precio: 0, stock: 0, imagen_url: '', id_categoria: 1, id_disenador: 1 });
  const [formDis, setFormDis] = useState({ nombre: '', biografia: '', web_url: '' });

  // Data Fetching
  const fetchData = async () => {
    setLoading(true);
    try {
      if (activeTab === 'productos') {
        const res = await apiClient.get('/productos');
        setProductos(res.data);
      } else if (activeTab === 'usuarios') {
        const res = await apiClient.get('/usuarios');
        setUsuarios(res.data);
      } else if (activeTab === 'disenadores') {
        const res = await apiClient.get('/disenadores');
        setDisenadores(res.data);
      } else if (activeTab === 'pedidos') {
        const res = await apiClient.get('/pedidos');
        setPedidos(res.data);
      }
    } catch (err) {
      console.error('Error fetching data:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData();
  }, [activeTab]);

  // -------- ACTIONS: PRODUCTOS --------
  const handleDeleteProd = async (id: number) => {
    if (!window.confirm('¿Seguro que deseas eliminar el producto?')) return;
    try { await apiClient.delete(`/productos/${id}`); fetchData(); } catch (e) { alert('Error eliminando producto'); }
  };
  const handleOpenProd = (p?: Producto) => {
    setEditingId(p ? p.id_producto : null);
    setFormProd(p ? { nombre: p.nombre, descripcion: p.descripcion||'', precio: p.precio, stock: p.stock, imagen_url: p.imagen_url||'', id_categoria: p.id_categoria, id_disenador: p.id_disenador||1 } 
                  : { nombre: '', descripcion: '', precio: 0, stock: 0, imagen_url: '', id_categoria: 1, id_disenador: 1 });
    setIsModalProdOpen(true);
  };
  const handleSubmitProd = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (editingId) await apiClient.put(`/productos/${editingId}`, formProd);
      else await apiClient.post('/productos', formProd);
      setIsModalProdOpen(false);
      fetchData();
    } catch (err) { alert('Error al guardar el producto'); }
  };

  // -------- ACTIONS: DISEÑADORES --------
  const handleDeleteDis = async (id: number) => {
    if (!window.confirm('¿Seguir con la eliminación?')) return;
    try { await apiClient.delete(`/disenadores/${id}`); fetchData(); } catch (e: any) { alert(e.response?.data?.error || 'Error eliminando'); }
  };
  const handleOpenDis = (d?: Disenador) => {
    setEditingId(d ? d.id_disenador : null);
    setFormDis(d ? { nombre: d.nombre, biografia: d.biografia||'', web_url: d.web_url||'' } : { nombre: '', biografia: '', web_url: '' });
    setIsModalDisOpen(true);
  };
  const handleSubmitDis = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (editingId) await apiClient.put(`/disenadores/${editingId}`, formDis);
      else await apiClient.post('/disenadores', formDis);
      setIsModalDisOpen(false);
      fetchData();
    } catch (err) { alert('Error al guardar diseñador'); }
  };

  // -------- ACTIONS: USUARIOS --------
  const handleRoleChange = async (id: number, newRole: string) => {
    try { await apiClient.put(`/usuarios/${id}/rol`, { rol: newRole }); fetchData(); alert('Rol actualizado'); } 
    catch (err) { alert('Error cambiando rol'); }
  };
  const handleDeleteUsr = async (id: number) => {
    if (!window.confirm('¿Eliminar usuario?')) return;
    try { await apiClient.delete(`/usuarios/${id}`); fetchData(); } catch (e) { alert('Error al eliminar'); }
  };

  // -------- ACTIONS: PEDIDOS --------
  const handleStatusChange = async (id: number, newStatus: string) => {
    try { await apiClient.put(`/pedidos/${id}/estado`, { estado: newStatus }); fetchData(); alert('Estado actualizado'); } 
    catch (err) { alert('Error cambiando estado'); }
  };

  // Renderers Base
  const tabBtnStyle = (tab: TabType) => ({
    padding: '12px 20px', background: activeTab === tab ? '#111' : '#eee', 
    color: activeTab === tab ? '#fff' : '#333', border: 'none', 
    borderRadius: '8px 8px 0 0', fontWeight: 'bold', cursor: 'pointer'
  });

  return (
    <div style={{ padding: '120px 50px 50px', minHeight: '100vh', background: 'linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%)', fontFamily: "'Helvetica', sans-serif" }}>
      <div style={{ maxWidth: '1200px', margin: '0 auto', background: 'rgba(255, 255, 255, 0.7)', backdropFilter: 'blur(20px)', borderRadius: '24px', padding: '40px', boxShadow: '0 20px 40px rgba(0,0,0,0.05)', border: '1px solid rgba(255,255,255,0.4)' }}>
        
        <h2 style={{ fontSize: '32px', fontWeight: '900', letterSpacing: '-1px', margin: '0 0 30px 0', color: '#111' }}>
          Suite Administrativa
        </h2>

        <div style={{ display: 'flex', gap: '5px', borderBottom: '2px solid #111', marginBottom: '30px' }}>
          <button style={tabBtnStyle('productos')} onClick={() => setActiveTab('productos')}>🛍️ Productos</button>
          <button style={tabBtnStyle('pedidos')} onClick={() => setActiveTab('pedidos')}>📦 Pedidos</button>
          <button style={tabBtnStyle('usuarios')} onClick={() => setActiveTab('usuarios')}>👥 Usuarios</button>
          <button style={tabBtnStyle('disenadores')} onClick={() => setActiveTab('disenadores')}>✨ Diseñadores</button>
        </div>

        {loading ? <p>Cargando datos...</p> : (
          <div style={{ overflowX: 'auto' }}>
            {/* VIEW PRODUCTOS */}
            {activeTab === 'productos' && (
              <>
                <button onClick={() => handleOpenProd()} style={{ background: '#000', color: '#fff', border: 'none', padding: '10px 20px', borderRadius: '4px', fontWeight: 'bold', cursor: 'pointer', marginBottom: '20px' }}>+ Nuevo Producto</button>
                <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left' }}>
                  <thead><tr style={{ borderBottom: '2px solid #000' }}>
                    <th style={{ padding: '15px' }}>ID</th><th style={{ padding: '15px' }}>Nombre</th><th style={{ padding: '15px' }}>Stock</th><th style={{ padding: '15px' }}>Precio</th><th style={{ padding: '15px' }}>Acciones</th>
                  </tr></thead>
                  <tbody>{productos.map(p => (
                    <tr key={p.id_producto} style={{ borderBottom: '1px solid #ddd' }}>
                      <td style={{ padding: '15px' }}>#{p.id_producto}</td>
                      <td style={{ padding: '15px', fontWeight: 'bold' }}>{p.nombre}</td>
                      <td style={{ padding: '15px' }}>{p.stock}</td><td style={{ padding: '15px' }}>{p.precio}€</td>
                      <td style={{ padding: '15px', display:'flex', gap:'10px' }}>
                        <button onClick={() => handleOpenProd(p)} style={{background: '#eee', border:'none', padding:'8px 12px', cursor:'pointer', fontWeight:'bold', borderRadius:'4px'}}>Editar</button>
                        <button onClick={() => handleDeleteProd(p.id_producto)} style={{background: '#ff4d4d', color:'white', border:'none', padding:'8px 12px', cursor:'pointer', fontWeight:'bold', borderRadius:'4px'}}>Eliminar</button>
                      </td>
                    </tr>
                  ))}</tbody>
                </table>
              </>
            )}

            {/* VIEW PEDIDOS */}
            {activeTab === 'pedidos' && (
              <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left' }}>
                <thead><tr style={{ borderBottom: '2px solid #000' }}>
                  <th style={{ padding: '15px' }}>Pedido #</th><th style={{ padding: '15px' }}>Fecha</th><th style={{ padding: '15px' }}>Cliente</th><th style={{ padding: '15px' }}>Total</th><th style={{ padding: '15px' }}>Estado</th>
                </tr></thead>
                <tbody>{pedidos.map(p => (
                  <tr key={p.id_pedido} style={{ borderBottom: '1px solid #ddd' }}>
                    <td style={{ padding: '15px' }}><b>#{p.id_pedido}</b></td>
                    <td style={{ padding: '15px' }}>{new Date(p.fecha_pedido).toLocaleDateString()}</td>
                    <td style={{ padding: '15px' }}>{p.cliente}<br/><small>{p.email}</small></td>
                    <td style={{ padding: '15px', fontWeight: 'bold' }}>{p.total}€</td>
                    <td style={{ padding: '15px' }}>
                      <select value={p.estado} onChange={(e) => handleStatusChange(p.id_pedido, e.target.value)} style={{ padding: '8px', borderRadius: '4px', border: '1px solid #aaa' }}>
                        <option value="pendiente">Pendiente</option><option value="pagado">Pagado</option>
                        <option value="enviado">Enviado</option><option value="completado">Completado</option>
                        <option value="cancelado">Cancelado</option>
                      </select>
                    </td>
                  </tr>
                ))}</tbody>
              </table>
            )}

            {/* VIEW USUARIOS */}
            {activeTab === 'usuarios' && (
              <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left' }}>
                <thead><tr style={{ borderBottom: '2px solid #000' }}>
                  <th style={{ padding: '15px' }}>ID</th><th style={{ padding: '15px' }}>Usuario</th><th style={{ padding: '15px' }}>Rol</th><th style={{ padding: '15px' }}>Acciones</th>
                </tr></thead>
                <tbody>{usuarios.map(u => (
                  <tr key={u.id_usuario} style={{ borderBottom: '1px solid #ddd' }}>
                    <td style={{ padding: '15px' }}>#{u.id_usuario}</td>
                    <td style={{ padding: '15px' }}><b>{u.nombre}</b><br/>{u.email}</td>
                    <td style={{ padding: '15px' }}>
                      <select value={u.rol} onChange={(e) => handleRoleChange(u.id_usuario, e.target.value)} style={{ padding: '8px', borderRadius: '4px', border: '1px solid #aaa' }}>
                        <option value="cliente">Cliente</option><option value="admin">Admin</option>
                      </select>
                    </td>
                    <td style={{ padding: '15px' }}>
                      <button onClick={() => handleDeleteUsr(u.id_usuario)} style={{background: '#ff4d4d', color:'white', border:'none', padding:'8px 12px', cursor:'pointer', fontWeight:'bold', borderRadius:'4px'}}>Expulsar</button>
                    </td>
                  </tr>
                ))}</tbody>
              </table>
            )}

            {/* VIEW DISEÑADORES */}
            {activeTab === 'disenadores' && (
              <>
                <button onClick={() => handleOpenDis()} style={{ background: '#000', color: '#fff', border: 'none', padding: '10px 20px', borderRadius: '4px', fontWeight: 'bold', cursor: 'pointer', marginBottom: '20px' }}>+ Nuevo Diseñador</button>
                <table style={{ width: '100%', borderCollapse: 'collapse', textAlign: 'left' }}>
                  <thead><tr style={{ borderBottom: '2px solid #000' }}>
                    <th style={{ padding: '15px' }}>ID</th><th style={{ padding: '15px' }}>Nombre</th><th style={{ padding: '15px' }}>Web</th><th style={{ padding: '15px' }}>Acciones</th>
                  </tr></thead>
                  <tbody>{disenadores.map(d => (
                    <tr key={d.id_disenador} style={{ borderBottom: '1px solid #ddd' }}>
                      <td style={{ padding: '15px' }}>#{d.id_disenador}</td>
                      <td style={{ padding: '15px', fontWeight: 'bold' }}>{d.nombre}</td>
                      <td style={{ padding: '15px' }}>{d.web_url || 'N/A'}</td>
                      <td style={{ padding: '15px', display:'flex', gap:'10px' }}>
                        <button onClick={() => handleOpenDis(d)} style={{background: '#eee', border:'none', padding:'8px 12px', cursor:'pointer', fontWeight:'bold', borderRadius:'4px'}}>Editar</button>
                        <button onClick={() => handleDeleteDis(d.id_disenador)} style={{background: '#ff4d4d', color:'white', border:'none', padding:'8px 12px', cursor:'pointer', fontWeight:'bold', borderRadius:'4px'}}>Eliminar</button>
                      </td>
                    </tr>
                  ))}</tbody>
                </table>
              </>
            )}
          </div>
        )}
      </div>

      {/* MODAL PRODUCTOS */}
      {isModalProdOpen && (
        <div style={{ position: 'fixed', top: 0, left: 0, width: '100vw', height: '100vh', background: 'rgba(0,0,0,0.6)', backdropFilter: 'blur(8px)', display: 'flex', justifyContent: 'center', alignItems: 'center', zIndex: 9999 }}>
          <div style={{ background: '#fff', padding: '40px', borderRadius: '16px', width: '100%', maxWidth: '500px', boxShadow: '0 25px 50px rgba(0,0,0,0.2)' }}>
            <h3 style={{ marginTop: 0 }}>{editingId ? 'Editar Producto' : 'Nuevo Producto'}</h3>
            <form onSubmit={handleSubmitProd} style={{ display: 'flex', flexDirection: 'column', gap: '15px' }}>
              <input required placeholder="Nombre" value={formProd.nombre} onChange={e => setFormProd({...formProd, nombre: e.target.value})} style={{ padding:'10px', border:'1px solid #ccc', borderRadius:'4px' }} />
              <input type="number" required placeholder="Precio" value={formProd.precio} onChange={e => setFormProd({...formProd, precio: parseFloat(e.target.value)})} style={{ padding:'10px', border:'1px solid #ccc', borderRadius:'4px' }} />
              <input type="number" required placeholder="Stock" value={formProd.stock} onChange={e => setFormProd({...formProd, stock: parseInt(e.target.value)})} style={{ padding:'10px', border:'1px solid #ccc', borderRadius:'4px' }} />
              <div style={{ display: 'flex', gap: '10px', marginTop: '10px' }}>
                <button type="button" onClick={() => setIsModalProdOpen(false)} style={{ flex: 1, padding: '12px', background: '#eee', border:'none', borderRadius: '4px', fontWeight: 'bold', cursor: 'pointer' }}>Cancelar</button>
                <button type="submit" style={{ flex: 1, padding: '12px', background: '#000', color: '#fff', border:'none', borderRadius: '4px', fontWeight: 'bold', cursor: 'pointer' }}>Guardar</button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* MODAL DISEÑADORES */}
      {isModalDisOpen && (
        <div style={{ position: 'fixed', top: 0, left: 0, width: '100vw', height: '100vh', background: 'rgba(0,0,0,0.6)', backdropFilter: 'blur(8px)', display: 'flex', justifyContent: 'center', alignItems: 'center', zIndex: 9999 }}>
          <div style={{ background: '#fff', padding: '40px', borderRadius: '16px', width: '100%', maxWidth: '400px', boxShadow: '0 25px 50px rgba(0,0,0,0.2)' }}>
            <h3 style={{ marginTop: 0 }}>{editingId ? 'Editar Diseñador' : 'Nuevo Diseñador'}</h3>
            <form onSubmit={handleSubmitDis} style={{ display: 'flex', flexDirection: 'column', gap: '15px' }}>
              <input required placeholder="Nombre" value={formDis.nombre} onChange={e => setFormDis({...formDis, nombre: e.target.value})} style={{ padding:'10px', border:'1px solid #ccc', borderRadius:'4px' }} />
              <textarea placeholder="Biografía" value={formDis.biografia} onChange={e => setFormDis({...formDis, biografia: e.target.value})} style={{ padding:'10px', border:'1px solid #ccc', borderRadius:'4px' }} />
              <input placeholder="URL Sitio Web" value={formDis.web_url} onChange={e => setFormDis({...formDis, web_url: e.target.value})} style={{ padding:'10px', border:'1px solid #ccc', borderRadius:'4px' }} />
              <div style={{ display: 'flex', gap: '10px', marginTop: '10px' }}>
                <button type="button" onClick={() => setIsModalDisOpen(false)} style={{ flex: 1, padding: '12px', background: '#eee', border:'none', borderRadius: '4px', fontWeight: 'bold', cursor: 'pointer' }}>Cancelar</button>
                <button type="submit" style={{ flex: 1, padding: '12px', background: '#000', color: '#fff', border:'none', borderRadius: '4px', fontWeight: 'bold', cursor: 'pointer' }}>Guardar</button>
              </div>
            </form>
          </div>
        </div>
      )}

    </div>
  );
};
export default AdminPanel;
