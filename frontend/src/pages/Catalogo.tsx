import { useState, useEffect, useContext } from 'react';
import apiClient from '../api/client';
import type { Producto } from '../types';
import { CartContext } from '../context/CartContext';
import { AuthContext } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';

const Catalogo = () => {
  const [productos, setProductos] = useState<Producto[]>([]);
  const [query, setQuery] = useState('');
  const { addToCart } = useContext(CartContext);
  const { token } = useContext(AuthContext);
  const navigate = useNavigate();

  const fetchProductos = async (searchQuery = '') => {
    try {
      const res = await apiClient.get(`/productos${searchQuery ? `?q=${searchQuery}` : ''}`);
      setProductos(res.data);
    } catch (err) {
      console.error(err);
    }
  };

  useEffect(() => {
    fetchProductos();
  }, []);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    fetchProductos(query);
  };

  const handleAddToCart = (id: number) => {
    if (!token) {
      navigate('/login');
      return;
    }
    addToCart(id);
  };

  return (
    <main className="catalogo-container">
      <header className="catalogo-header">
        <div style={{ display: 'flex', alignItems: 'center', gap: '30px' }}>
          <h2>TIENDA</h2>

          <form onSubmit={handleSearch} className="search-form">
            <input
              type="text"
              placeholder="BUSCAR PRODUCTO..."
              className="search-input"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
            />
            <button type="submit" className="search-button">🔍</button>
          </form>
        </div>
        <p style={{ fontWeight: 700, fontSize: '13px', color: '#666' }}>
          {productos.length} PRODUCTOS ENCONTRADOS
        </p>
      </header>

      <div className="productos-grid">
        {productos.map((p) => (
          <div key={p.id_producto} className="card-producto">
            <div className="card-imagen-wrapper">
              {p.imagen_url ? (
                <img src={`/img/productos/${p.imagen_url}`} alt={p.nombre} />
              ) : (
                <span style={{ fontSize: '60px' }}>👕</span>
              )}
            </div>
            
            <h3 className="card-titulo">{p.nombre}</h3>
            <p className="card-precio">{Number(p.precio).toFixed(2)}€</p>
            <p className="card-descripcion">{p.descripcion}</p>

            <button onClick={() => handleAddToCart(p.id_producto)} className="btn-add-cart">
              Añadir a la Cesta
            </button>
          </div>
        ))}

        {productos.length === 0 && (
          <div style={{ gridColumn: '1/-1', textAlign: 'center', padding: '100px 0' }}>
            <p style={{ fontSize: '18px', color: '#999' }}>No hay productos que coincidan con tu búsqueda.</p>
          </div>
        )}
      </div>
    </main>
  );
};

export default Catalogo;
