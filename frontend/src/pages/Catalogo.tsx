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
    <main className="pt-[120px] px-[50px] pb-[50px] bg-gray-50 max-md-px-5 max-md-pt-[100px] max-md-pb-[30px]">
      <header className="flex justify-between items-center mb-[50px] border-b-2 border-black pb-5">
        <h2 className="m-0 text-[28px] font-black tracking-tight">TIENDA</h2>
        <div className="flex items-center gap-[30px]">
          <form onSubmit={handleSearch} className="flex bg-white border border-black p-[5px]">
            <input
              type="text"
              placeholder="BUSCAR PRODUCTO..."
              className="border-none p-2.5 outline-none w-[250px] font-semibold max-md-w-[150px]"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
            />
            <button type="submit" className="bg-black text-white border-none px-5 cursor-pointer font-bold">🔍</button>
          </form>
          <p className="font-bold text-xs text-gray-500">{productos.length} PRODUCTOS ENCONTRADOS</p>
        </div>
      </header>

      <div className="grid grid-cols-[repeat(auto-fill,minmax(280px,1fr))] gap-10 max-md-grid-cols-[repeat(auto-fill,minmax(200px,1fr))] max-md-gap-5">
        {productos.map((p) => (
          <div key={p.id_producto} className="bg-white p-5 border border-gray-200 text-center transition-all hover:shadow-lg hover:-translate-y-1">
            <div className="h-[300px] bg-gray-100 mb-5 flex items-center justify-center overflow-hidden">
              {p.imagen_url ? (
                <img src={`/img/productos/${p.imagen_url}`} alt={p.nombre} className="max-w-full max-h-full object-cover" />
              ) : (
                <span className="text-[60px]">👕</span>
              )}
            </div>
            
            <h3 className="text-sm uppercase my-2.5 tracking-wide h-[35px] overflow-hidden">{p.nombre}</h3>
            <p className="text-lg font-black my-2.5">{Number(p.precio).toFixed(2)}€</p>
            <p className="text-xs text-gray-400 mb-5 h-[30px] overflow-hidden">{p.descripcion}</p>

            <button onClick={() => handleAddToCart(p.id_producto)} className="w-full bg-black text-white border-none py-3.5 font-bold uppercase tracking-wide cursor-pointer transition-colors hover:bg-gray-800">
              Añadir a la Cesta
            </button>
          </div>
        ))}

        {productos.length === 0 && (
          <div className="col-span-full text-center py-[100px]">
            <p className="text-lg text-gray-400">No hay productos que coincidan con tu búsqueda.</p>
          </div>
        )}
      </div>
    </main>
  );
};

export default Catalogo;
