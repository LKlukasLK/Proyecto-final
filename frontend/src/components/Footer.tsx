import { Link } from 'react-router-dom';

const Footer = () => {
  return (
    <div className="bg-white text-black px-10 pt-15 pb-10 border-t border-gray-200 text-center">
        <div className="grid grid-cols-4 gap-7.5 max-w-1200 mx-auto mb-12.5 justify-items-center max-md-grid-cols-2">
            <div>
                <h3 className="text-sm font-extrabold uppercase mb-5 text-black">Recursos</h3>
                <ul className="list-none p-0 m-0">
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Tarjetas de regalo</Link></li>
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Buscar una tienda</Link></li>
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Journal</Link></li>
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Hazte Member</Link></li>
                </ul>
            </div>
            <div>
                <h3 className="text-sm font-extrabold uppercase mb-5 text-black">Ayuda</h3>
                <ul className="list-none p-0 m-0">
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Obtener ayuda</Link></li>
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Estado del pedido</Link></li>
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Envíos y entregas</Link></li>
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Devoluciones</Link></li>
                </ul>
            </div>
            <div>
                <h3 className="text-sm font-extrabold uppercase mb-5 text-black">Empresa</h3>
                <ul className="list-none p-0 m-0">
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Acerca de nosotros</Link></li>
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Novedades</Link></li>
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Empleo</Link></li>
                    <li className="mb-3"><Link to="#" className="text-gray-500 no-underline text-xs font-semibold hover-text-black">Sostenibilidad</Link></li>
                </ul>
            </div>
        </div>

        <div className="max-w-1200 mx-auto pt-6.25 border-t border-gray-200 flex justify-center items-center flex-wrap gap-7.5 max-md-flex-col max-md-gap-3.75">
            <footer>
                <p className="text-xs font-extrabold text-black m-0">&copy; 2026 Mercado Ropa - React SPA &race;</p>
            </footer>
            <nav className="flex gap-5 justify-center">
                <Link to="#" className="text-black no-underline text-xs font-bold hover-underline">Guías</Link>
                <Link to="#" className="text-black no-underline text-xs font-bold hover-underline">Términos de uso</Link>
                <Link to="#" className="text-black no-underline text-xs font-bold hover-underline">Aviso legal</Link>
                <Link to="#" className="text-black no-underline text-xs font-bold hover-underline">Privacidad y cookies</Link>
            </nav>
        </div>
    </div>
  );
};

export default Footer;
