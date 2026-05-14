import { Link } from 'react-router-dom';

const Home = () => {
  return (
    <main className="bg-white m-0 pt-[50px] overflow-x-hidden">
      <div className="relative w-full py-10 pb-15 overflow-hidden">
        <div className="w-[120%] -ml-[10%] bg-black py-6 -rotate-[2.5deg] flex justify-center items-center shadow-lg">
          <h2 className="text-[6vw] text-white m-0 uppercase font-black tracking-wide whitespace-nowrap max-md-text-[10vw]">
            Bienvenidos a Mercado Ropa
          </h2>
        </div>
      </div>

      <div className="text-center px-5 pb-[50px]">
        <p className="max-w-[800px] mx-auto text-[1.2rem] text-gray-500 leading-relaxed">
          Tu tienda exclusiva de moda. Diseños únicos y exclusivos seleccionados para ti.
        </p>
      </div>

      <div className="w-full h-[55vh] overflow-hidden">
        <img src="/img/imagen1.jpg" alt="Superior" className="w-full h-full object-cover" />
      </div>

      <section className="max-w-[800px] mx-auto px-5 py-20 text-center">
        <h2 className="text-4xl text-gray-900 mb-6 font-extrabold uppercase">Innovación y Estilo</h2>
        <p className="text-lg text-gray-600 leading-relaxed mb-10">
          Descubre nuestra nueva colección de temporada. Materiales premium y 
          confección de alta calidad para destacar en cada momento.
        </p>
        <Link to="/catalogo" className="inline-block bg-black text-white px-[50px] py-3.5 text-base font-bold rounded no-underline uppercase transition-colors border-2 border-black hover:bg-white hover:text-black">
          Comprar Ahora
        </Link>
      </section>

      <div className="w-full h-[55vh] overflow-hidden">
        <img src="/img/imagen3.jpg" alt="Inferior" className="w-full h-full object-cover" />
      </div>
    </main>
  );
};

export default Home;
