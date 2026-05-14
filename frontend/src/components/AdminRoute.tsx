import { ReactNode, useContext } from 'react';
import { Navigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';

const AdminRoute = ({ children }: { children: ReactNode }) => {
  const { user, loading } = useContext(AuthContext);

  if (loading) {
    return <p>Cargando...</p>;
  }

  if (!user) {
    return <Navigate to="/login" replace />;
  }

  if (user.rol !== 'admin') {
    return <Navigate to="/" replace />;
  }

  return <>{children}</>;
};

export default AdminRoute;
