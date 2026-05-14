import { Link, useSearchParams } from 'react-router-dom';

const PagoExitoso = () => {
  const [searchParams] = useSearchParams();
  const orderId = searchParams.get('id');

  return (
    <main className="pt-[120px] px-[50px] pb-[50px] bg-gray-50">
      <header className="flex justify-between items-center mb-[50px] border-b-2 border-black pb-5">
        <h2 className="m-0 text-[28px] font-black tracking-tight">Pago completado</h2>
      </header>
      <div className="text-center py-10">
        <p className="text-lg text-gray-700 mb-2.5">¡Gracias por tu pedido{orderId ? `! Número de pedido: ${orderId}` : '!'}</p>
        <p className="text-base text-gray-500 mb-5">Tu pago se ha procesado correctamente.</p>
        <Link to="/catalogo" className="inline-block px-[30px] py-3 bg-black text-white font-extrabold text-xs uppercase tracking-wide no-underline border-2 border-black transition-all hover:bg-white hover:text-black">
          Volver a la tienda
        </Link>
      </div>
    </main>
  );
};

export default PagoExitoso;
