import { Link } from 'react-router-dom';

const Footer = () => {
  return (
    <div className="footer-nike-white">
        <div className="footer-columns">
            <div className="footer-column">
                <h3>Recursos</h3>
                <ul>
                    <li><Link to="#">Tarjetas de regalo</Link></li>
                    <li><Link to="#">Buscar una tienda</Link></li>
                    <li><Link to="#">Journal</Link></li>
                    <li><Link to="#">Hazte Member</Link></li>
                </ul>
            </div>
            <div className="footer-column">
                <h3>Ayuda</h3>
                <ul>
                    <li><Link to="#">Obtener ayuda</Link></li>
                    <li><Link to="#">Estado del pedido</Link></li>
                    <li><Link to="#">Envíos y entregas</Link></li>
                    <li><Link to="#">Devoluciones</Link></li>
                </ul>
            </div>
            <div className="footer-column">
                <h3>Empresa</h3>
                <ul>
                    <li><Link to="#">Acerca de nosotros</Link></li>
                    <li><Link to="#">Novedades</Link></li>
                    <li><Link to="#">Empleo</Link></li>
                    <li><Link to="#">Sostenibilidad</Link></li>
                </ul>
            </div>
        </div>

        <div className="footer-bottom-inline">
            <footer>
                <p>&copy; 2026 Mercado Ropa - React SPA &race;</p>
            </footer>
            <nav className="footer-legal-links">
                <Link to="#">Guías</Link>
                <Link to="#">Términos de uso</Link>
                <Link to="#">Aviso legal</Link>
                <Link to="#">Privacidad y cookies</Link>
            </nav>
        </div>
    </div>
  );
};

export default Footer;
