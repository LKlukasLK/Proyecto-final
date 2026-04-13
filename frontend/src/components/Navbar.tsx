import { useContext } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';
import { CartContext } from '../context/CartContext';

const Navbar = () => {
  const { user, logout } = useContext(AuthContext);
  const { totalItems } = useContext(CartContext);
  const navigate = useNavigate();

  return (
    <header className="main-header">
      <div className="nav-container">
        <div className="nav-group left">
          <Link to="/catalogo" className="nav-btn">TIENDA</Link>
          <Link to="/disenadores" className="nav-btn">DISEÑADORES</Link>
        </div>

        <div className="nav-group center">
          <Link to="/" className="brand-link">
            {/* Fallback to emoji if img isn't ready */}
            <h2>👕 Mercado Ropa</h2>
          </Link>
        </div>

        <div className="nav-group right">
          {user ? (
            <>
              {user.rol === 'admin' && (
                <Link to="/admin" className="nav-btn" style={{ color: '#e74c3c' }}>⭐ PANEL ADMIN</Link>
              )}
              <span className="nav-btn" style={{cursor:'default'}}>👤 {user.nombre}</span>
              <button onClick={() => { logout(); navigate('/'); }} className="nav-btn logout-btn">🚪 SALIR</button>
            </>
          ) : (
             <Link to="/login" className="nav-btn">ACCESO</Link>
          )}
          
          <Link to="/carrito" className="nav-btn">
            🛒 CESTA ({totalItems})
          </Link>
        </div>
      </div>
    </header>
  );
};

export default Navbar;
