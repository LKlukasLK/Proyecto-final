import { useState, useContext } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import apiClient from '../api/client';
import { AuthContext } from '../context/AuthContext';

const Login = () => {
  const [email, setEmail] = useState('');
  const [contrasena, setContrasena] = useState('');
  const [error, setError] = useState('');
  const { login } = useContext(AuthContext);
  const navigate = useNavigate();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const res = await apiClient.post('/auth/login', { email, contrasena });
      login(res.data.token, res.data.usuario);
      navigate('/');
    } catch (err: any) {
      setError(err.response?.data?.error || 'Error al iniciar sesión');
    }
  };

  return (
    <div className="main-centered">
      <div className="login-card">
        <h2 className="section-title">INGRESAR</h2>
        
        {error && <div className="alerta" style={{ color: 'red', marginBottom: '15px' }}>{error}</div>}

        <form onSubmit={handleSubmit}>
          <div className="field">
            <label>ESCRÍBENOS TU DIRECCIÓN DE CORREO</label>
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

          <button type="submit" className="btn-black">LOG IN</button>
        </form>

        <div className="extra-actions">
          <Link to="/registro" className="link-register">¿No tienes cuenta? Regístrate</Link>
        </div>
      </div>
    </div>
  );
};

export default Login;
