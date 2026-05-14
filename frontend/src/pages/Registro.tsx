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
    <div className="flex justify-center items-start px-5 py-[50px]">
      <div className="bg-white w-full max-w-[450px] p-10 rounded-lg shadow-lg text-center">
        <h2 className="text-2xl mb-[30px] font-extrabold uppercase">CREAR TU CUENTA</h2>
        
        {error && <div className="text-red-500 mb-3.75">{error}</div>}

        <form onSubmit={handleSubmit}>
          <div className="text-left mb-5">
            <label className="block text-xs font-bold uppercase mb-2">NOMBRE COMPLETO</label>
            <input 
              type="text" 
              required 
              value={nombre}
              onChange={e => setNombre(e.target.value)}
              className="w-full p-3 border border-gray-300 rounded outline-none focus:border-black text-[15px]"
            />
          </div>

          <div className="text-left mb-5">
            <label className="block text-xs font-bold uppercase mb-2">DIRECCIÓN DE CORREO</label>
            <input 
              type="email" 
              required 
              value={email}
              onChange={e => setEmail(e.target.value)}
              className="w-full p-3 border border-gray-300 rounded outline-none focus:border-black text-[15px]"
            />
          </div>
          
          <div className="text-left mb-5">
            <label className="block text-xs font-bold uppercase mb-2">CONTRASEÑA</label>
            <input 
              type="password" 
              required 
              value={contrasena}
              onChange={e => setContrasena(e.target.value)}
              className="w-full p-3 border border-gray-300 rounded outline-none focus:border-black text-[15px]"
            />
          </div>

          <button type="submit" className="w-full bg-black text-white py-3.75 border-none rounded font-bold uppercase cursor-pointer mt-2.5 transition-colors hover:bg-gray-800">UNIRSE AHORA</button>
        </form>

        <div className="mt-5 pt-5 border-t border-gray-200">
          <Link to="/login" className="text-black font-bold no-underline hover:underline">¿Ya eres member? Inicia sesión</Link>
        </div>
      </div>
    </div>
  );
};

export default Registro;
