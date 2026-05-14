import { Link } from 'react-router-dom';
import { useContext } from 'react';
import { AuthContext } from '../context/AuthContext';
import AdminRoute from '../components/AdminRoute';

const AdminDashboard = () => {
  const { user } = useContext(AuthContext);

  return (
    <AdminRoute>
      <main className="pt-[120px] px-[50px] pb-[50px] bg-gray-50">
        <header className="flex justify-between items-center mb-[50px] border-b-2 border-black pb-5">
          <h2 className="m-0 text-[28px] font-black tracking-tight">Panel de administración</h2>
          <p>Hola {user?.nombre}, elige una sección para administrar.</p>
        </header>

        <div className="grid grid-cols-2 gap-5 max-w-[600px] mx-auto">
          <Link to="/admin/usuarios" className="bg-black text-white p-5 rounded text-center font-bold uppercase text-sm no-underline transition-colors hover:bg-gray-800">Gestionar usuarios</Link>
          <Link to="/admin/productos" className="bg-black text-white p-5 rounded text-center font-bold uppercase text-sm no-underline transition-colors hover:bg-gray-800">Crear y administrar productos</Link>
          <Link to="/admin/carritos" className="bg-black text-white p-5 rounded text-center font-bold uppercase text-sm no-underline transition-colors hover:bg-gray-800">Ver carritos de usuarios</Link>
          <Link to="/admin/pedidos" className="bg-black text-white p-5 rounded text-center font-bold uppercase text-sm no-underline transition-colors hover:bg-gray-800">Gestión de pedidos</Link>
        </div>
      </main>
    </AdminRoute>
  );
};

export default AdminDashboard;
