import { useContext, useEffect, useState } from 'react';
import { AuthContext } from '../context/AuthContext';
import AdminRoute from '../components/AdminRoute';
import apiClient from '../api/client';
import type { Usuario } from '../types';

const roles = ['cliente', 'admin'];

const AdminUsuarios = () => {
  const { user } = useContext(AuthContext);
  const [users, setUsers] = useState<Usuario[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const loadUsers = async () => {
    try {
      const res = await apiClient.get<Usuario[]>('/admin/usuarios');
      setUsers(res.data);
      setError('');
    } catch (err: any) {
      console.error('Error cargando usuarios:', err);
      setError(err.response?.data?.error || 'No se pudieron cargar los usuarios.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadUsers();
  }, []);

  const updateRole = async (id: number, rol: string) => {
    try {
      await apiClient.put(`/admin/usuarios/${id}`, { rol });
      setUsers((prev) => prev.map((u) => (u.id_usuario === id ? { ...u, rol } : u)));
      setError('');
    } catch (err: any) {
      console.error('Error actualizando rol:', err);
      setError(err.response?.data?.error || 'Error actualizando rol.');
    }
  };

  const deleteUsuario = async (id: number) => {
    if (user?.id_usuario === id) {
      setError('No puedes eliminar tu propio usuario.');
      return;
    }

    try {
      await apiClient.delete(`/admin/usuarios/${id}`);
      setUsers((prev) => prev.filter((u) => u.id_usuario !== id));
      setError('');
    } catch (err: any) {
      console.error('Error eliminando usuario:', err);
      setError(err.response?.data?.error || 'Error eliminando usuario.');
    }
  };

  return (
    <AdminRoute>
      <main className="pt-[120px] px-[50px] pb-[50px] bg-gray-50">
        <header className="flex justify-between items-center mb-[50px] border-b-2 border-black pb-5">
          <h2 className="m-0 text-[28px] font-black tracking-tight">Usuarios</h2>
          <p className="text-gray-600 text-sm">Gestiona roles y usuarios registrados.</p>
        </header>

        {error && <p className="text-red-500 text-sm mb-2.5">{error}</p>}

        {loading ? (
          <p className="text-gray-500">Cargando usuarios...</p>
        ) : (
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
                <span className="flex-1">
                  <select
                    value={usuario.rol}
                    onChange={(e) => updateRole(usuario.id_usuario, e.target.value)}
                    className="p-1.25 border border-gray-300 rounded text-xs"
                  >
                    {roles.map((rol) => (
                      <option key={rol} value={rol}>{rol}</option>
                    ))}
                  </select>
                </span>
                <span className="flex-1">
                  <button
                    onClick={() => deleteUsuario(usuario.id_usuario)}
                    className="px-2.5 py-1.25 bg-red-600 text-white text-xs font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80 disabled:opacity-50"
                    disabled={user?.id_usuario === usuario.id_usuario}
                  >
                    Eliminar
                  </button>
                </span>
              </div>
            ))}
          </div>
        )}
      </main>
    </AdminRoute>
  );
};

export default AdminUsuarios;
