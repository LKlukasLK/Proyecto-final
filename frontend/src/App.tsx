import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Navbar from './components/Navbar';
import Footer from './components/Footer';
import Home from './pages/Home';
import Catalogo from './pages/Catalogo';
import Login from './pages/Login';
import Registro from './pages/Registro';
import Carrito from './pages/Carrito';
import Disenadores from './pages/Disenadores';
import PagoExitoso from './pages/PagoExitoso';
import AdminDashboard from './pages/AdminDashboard';
import AdminUsuarios from './pages/AdminUsuarios';
import AdminProductos from './pages/AdminProductos';
import AdminCarritos from './pages/AdminCarritos';
import AdminPedidos from './pages/AdminPedidos';

function App() {
  return (
    <BrowserRouter>
      <div className="app-container">
        <Navbar />
        <main className="main-content">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/catalogo" element={<Catalogo />} />
            <Route path="/disenadores" element={<Disenadores />} />
            <Route path="/login" element={<Login />} />
            <Route path="/registro" element={<Registro />} />
            <Route path="/carrito" element={<Carrito />} />
            <Route path="/pago-exitoso" element={<PagoExitoso />} />
            <Route path="/admin" element={<AdminDashboard />} />
            <Route path="/admin/usuarios" element={<AdminUsuarios />} />
            <Route path="/admin/productos" element={<AdminProductos />} />
            <Route path="/admin/carritos" element={<AdminCarritos />} />
            <Route path="/admin/pedidos" element={<AdminPedidos />} />
          </Routes>
        </main>
        <Footer />
      </div>
    </BrowserRouter>
  );
}

export default App;
