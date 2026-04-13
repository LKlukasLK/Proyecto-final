import { useState, useEffect } from 'react';
import apiClient from '../api/client';
import type { Disenador } from '../types';

const Disenadores = () => {
  const [disenadores, setDisenadores] = useState<Disenador[]>([]);

  useEffect(() => {
    const fetchDisenadores = async () => {
      try {
        const res = await apiClient.get<Disenador[]>('/disenadores');
        setDisenadores(res.data);
      } catch (err) {
        console.error(err);
      }
    };
    fetchDisenadores();
  }, []);

  return (
    <main className="catalogo-container">
      <header className="catalogo-header">
        <h2>NUESTROS DISEÑADORES</h2>
      </header>

      <div className="productos-grid">
        {disenadores.map((d) => (
          <div key={d.id_disenador} className="card-producto" style={{ padding: '30px', textAlign: 'left' }}>
            <h3 className="card-titulo" style={{ fontSize: '20px', height: 'auto', marginBottom: '15px' }}>{d.nombre}</h3>
            <p className="card-descripcion" style={{ height: 'auto', marginBottom: '20px', color: '#555', lineHeight: '1.5' }}>
              {d.biografia}
            </p>
            {d.web_url && (
              <a href={d.web_url} target="_blank" rel="noreferrer" className="btn-interes" style={{ textDecoration: 'none', display: 'inline-block', color: 'black', background: '#eee', padding: '10px 15px' }}>
                Visitar Web
              </a>
            )}
          </div>
        ))}
      </div>
    </main>
  );
};

export default Disenadores;
