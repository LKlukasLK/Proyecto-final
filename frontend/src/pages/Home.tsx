import { Link } from 'react-router-dom';

const Home = () => {
  return (
    <main className="info-page-main">
      <div className="info-page-header-wrapper">
        <div className="info-page-skew-bar">
          <h2>Bienvenidos a Mercado Ropa</h2>
        </div>
      </div>

      <div className="info-page-intro">
        <p>Tu tienda exclusiva de moda. Diseños únicos y exclusivos seleccionados para ti.</p>
      </div>

      <div className="info-page-image-box">
        <img src="/imagen3.jpg" alt="Superior" />
      </div>

      <section className="info-page-content-center">
        <h2 className="ultra-title">Innovación y Estilo</h2>
        <p className="premium-text">
          Descubre nuestra nueva colección de temporada. Materiales premium y
          confección de alta calidad para destacar en cada momento.
        </p>
        <Link to="/catalogo" className="info-page-button">Comprar Ahora</Link>
      </section>

      <div className="info-page-image-box">
        <img src="/imagen1.jpg" alt="Inferior" />
      </div>
    </main>
  );
};

export default Home;
