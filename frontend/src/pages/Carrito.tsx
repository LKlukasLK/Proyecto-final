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
      // Redirect to Stripe Checkout
      window.location.href = res.data.url;
    } catch (err) {
      console.error('Error in checkout:', err);
      alert('Hubo un error al preparar el pago.');
    }
  };

  return (
    <main className="catalogo-container">
      <header className="catalogo-header">
        <h2>TU CESTA</h2>
        <p className="articulos-count">{totalItems} ARTÍCULOS</p>
      </header>

      {items.length > 0 ? (
        <div className="carrito-lista">
          {items.map((item) => (
            <div key={item.id_producto} className="carrito-item">
              <div className="item-info">
                <div className="item-img-placeholder">
                  {item.imagen_url ? (
                    <img src={`/img/productos/${item.imagen_url}`} alt={item.nombre} />
                  ) : (
                    <span style={{ fontSize: '30px' }}>👕</span>
                  )}
                </div>
                <div className="item-detalles">
                  <h3>{item.nombre}</h3>
                  <p className="item-ref">CANTIDAD: {item.cantidad}</p>
                </div>
              </div>

              <div className="item-precio-wrap">
                <span className="item-precio">{(item.precio * item.cantidad).toFixed(2)}€</span>
                <button onClick={() => removeFromCart(item.id_producto)} className="btn-eliminar" style={{background:'none', cursor:'pointer'}}>
                  Eliminar
                </button>
              </div>
            </div>
          ))}

          <div className="carrito-resumen">
            <div className="resumen-fila">
              <span className="resumen-etiqueta">TOTAL </span>
              <span className="resumen-total">{totalPrice.toFixed(2)}€</span>
            </div>

            <button onClick={handleCheckout} className="btn-pagar">
              Tramitar Pedido
            </button>
          </div>
        </div>
      ) : (
        <div className="carrito-vacio-container">
          <p>Tu cesta está vacía actualmente.</p>
          <div className="contenedor-boton-volver">
            <Link to="/catalogo" className="btn-volver-tienda">VOLVER A LA TIENDA</Link>
          </div>
        </div>
      )}
    </main>
  );
};

export default Carrito;
