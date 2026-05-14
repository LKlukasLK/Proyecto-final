import { useContext } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { AuthContext } from '../context/AuthContext';
import { CartContext } from '../context/CartContext';

const Navbar = () => {
  const { user, logout } = useContext(AuthContext);
  const { totalItems } = useContext(CartContext);
  const navigate = useNavigate();

  return (
    <header className="fixed top-0 left-0 w-full bg-white border-b border-gray-200 z-50">
      <div className="flex justify-between items-center h-20 px-10">
        <div className="flex items-center flex-1 gap-5">
          <Link to="/catalogo" className="no-underline text-black font-bold text-xs bg-none border-none cursor-pointer flex items-center gap-2 uppercase">TIENDA</Link>
          <Link to="/disenadores" className="no-underline text-black font-bold text-xs bg-none border-none cursor-pointer flex items-center gap-2 uppercase">DISEÑADORES</Link>
        </div>

        <div className="flex items-center flex-1 gap-5 justify-center">
          <Link to="/" className="flex items-center gap-2.5 no-underline text-black">
            <h2 className="text-lg font-black m-0">👕 Mercado Ropa</h2>
          </Link>
        </div>

        <div className="flex items-center flex-1 gap-5 justify-end">
          {user ? (
            <>
              {user.rol === 'admin' && (
                <Link to="/admin" className="no-underline text-black font-bold text-xs bg-none border-none cursor-pointer flex items-center gap-2 uppercase">ADMIN</Link>
              )}
              <span className="no-underline text-black font-bold text-xs bg-none border-none flex items-center gap-2 uppercase cursor-default">👤 {user.nombre}</span>
              <button onClick={() => { logout(); navigate('/'); }} className="no-underline text-black font-bold text-xs bg-none border-none cursor-pointer flex items-center gap-2 uppercase">🚪 SALIR</button>
            </>
          ) : (
             <Link to="/login" className="no-underline text-black font-bold text-xs bg-none border-none cursor-pointer flex items-center gap-2 uppercase">ACCESO</Link>
          )}
          
          <Link to="/carrito" className="no-underline text-black font-bold text-xs bg-none border-none cursor-pointer flex items-center gap-2 uppercase">
            🛒 CESTA ({totalItems})
          </Link>
        </div>
      </div>
    </header>
  );
};

export default Navbar;
