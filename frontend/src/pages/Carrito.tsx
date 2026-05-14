import { useContext } from 'react';
import { CartContext } from '../context/CartContext';
import { Link } from 'react-router-dom';
import apiClient from '../api/client';

const Carrito = () => {
  const { items, totalItems, totalPrice, removeFromCart } = useContext(CartContext);

  const handleCheckout = async () => {
    try {
      const res = await apiClient.post('/pagos/preparar', {
        total: totalPrice,
        items: items
      });
      window.location.href = res.data.url;
    } catch (err: any) {
      console.error('Error in checkout:', err);
      const message = err.response?.data?.error || 'Hubo un error al preparar el pago.';
      alert(message);
    }
  };

  return (
    <main className="pt-[120px] px-[50px] pb-[50px] bg-gray-50">
      <header className="flex justify-between items-center mb-[50px] border-b-2 border-black pb-5">
        <h2 className="m-0 text-[28px] font-black tracking-tight">TU CESTA</h2>
        <p className="font-bold text-xs text-gray-500">{totalItems} ARTÍCULOS</p>
      </header>

      {items.length > 0 ? (
        <div>
          {items.map((item) => (
            <div key={item.id_producto} className="flex justify-between items-center bg-white p-5 mb-3.75 border border-gray-200 transition-all hover:shadow-md">
              <div className="flex items-center gap-5">
                <div className="w-[70px] h-[70px] bg-gray-50 flex justify-center items-center text-[30px]">
                  {item.imagen_url ? (
                    <img src={`/img/productos/${item.imagen_url}`} alt={item.nombre} className="w-full h-full object-cover" />
                  ) : (
                    <span>👕</span>
                  )}
                </div>
                <div>
                  <h3 className="text-base font-extrabold m-0 uppercase">{item.nombre}</h3>
                  <p className="text-gray-400 text-xs mt-1.25 m-0">CANTIDAD: {item.cantidad}</p>
                </div>
              </div>

              <div className="text-right">
                <span className="block text-lg font-black mb-2.5">{(item.precio * item.cantidad).toFixed(2)}€</span>
                <button onClick={() => removeFromCart(item.id_producto)} className="text-red-500 no-underline text-xs font-bold uppercase border border-red-500 px-2.5 py-1.25 cursor-pointer transition-all hover:bg-red-500 hover:text-white" style={{background:'none'}}>
                  Eliminar
                </button>
              </div>
            </div>
          ))}

          <div className="bg-black text-white p-[30px] mt-[30px] text-right">
            <div>
              <span className="text-2xl font-black ml-5">TOTAL </span>
              <span className="text-2xl font-black ml-5">{totalPrice.toFixed(2)}€</span>
            </div>

            <button onClick={handleCheckout} className="bg-white text-black border-none py-3.75 px-10 font-black uppercase mt-5 cursor-pointer w-full">
              Tramitar Pedido
            </button>
          </div>
        </div>
      ) : (
        <div className="py-10">
          <p className="text-base text-gray-600 mb-[30px]">Tu cesta está vacía actualmente.</p>
          <div className="flex justify-end mt-5">
            <Link to="/catalogo" className="inline-block px-[30px] py-3 bg-black text-white font-extrabold text-xs uppercase tracking-wide no-underline border-2 border-black transition-all hover:bg-white hover:text-black">
              VOLVER A LA TIENDA
            </Link>
          </div>
        </div>
      )}
    </main>
  );
};

export default Carrito;
