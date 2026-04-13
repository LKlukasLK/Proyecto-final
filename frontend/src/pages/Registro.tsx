import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import apiClient from '../api/client';

const Registro = () => {
  const [nombre, setNombre] = useState('');
  const [email, setEmail] = useState('');
  const [contrasena, setContrasena] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await apiClient.post('/auth/registro', { nombre, email, contrasena });
      navigate('/login');
    } catch (err: any) {
      setError(err.response?.data?.error || 'Error en el registro');
    }
  };

  return (
    <div className="main-centered">
      <div className="login-card">
        <h2 className="section-title">CREAR TU CUENTA</h2>
        
        {error && <div className="alerta" style={{ color: 'red', marginBottom: '15px' }}>{error}</div>}

        <form onSubmit={handleSubmit}>
          <div className="field">
            <label>NOMBRE COMPLETO</label>
            <input 
              type="text" 
              required 
              value={nombre}
              onChange={e => setNombre(e.target.value)}
            />
          </div>

          <div className="field">
            <label>DIRECCIÓN DE CORREO</label>
            <input 
              type="email" 
              required 
              value={email}
              onChange={e => setEmail(e.target.value)}
            />
          </div>
          
          <div className="field">
            <label>CONTRASEÑA</label>
            <input 
              type="password" 
              required 
              value={contrasena}
              onChange={e => setContrasena(e.target.value)}
            />
          </div>

          <button type="submit" className="btn-black">UNIRSE AHORA</button>
        </form>

        <div className="extra-actions">
          <Link to="/login" className="link-register">¿Ya eres member? Inicia sesión</Link>
        </div>
      </div>
    </div>
  );
};

export default Registro;
