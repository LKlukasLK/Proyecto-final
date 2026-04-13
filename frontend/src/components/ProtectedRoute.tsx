import { useContext } from 'react';
import { Navigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';

interface ProtectedRouteProps {
  children: JSX.Element;
  roleRequired?: string;
}

const ProtectedRoute = ({ children, roleRequired }: ProtectedRouteProps) => {
  const { user, loading } = useContext(AuthContext);

  if (loading) {
    return <div style={{ padding: '100px', textAlign: 'center' }}>Cargando...</div>;
  }

  if (!user) {
    return <Navigate to="/login" replace />;
  }

  if (roleRequired && user.rol !== roleRequired) {
    return <Navigate to="/" replace />;
  }

  return children;
};

export default ProtectedRoute;
