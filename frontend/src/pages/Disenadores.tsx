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
    <main className="pt-[120px] px-[50px] pb-[50px] bg-gray-50">
      <header className="flex justify-between items-center mb-[50px] border-b-2 border-black pb-5">
        <h2 className="m-0 text-[28px] font-black tracking-tight">NUESTROS DISEÑADORES</h2>
      </header>

      <div className="grid grid-cols-[repeat(auto-fill,minmax(280px,1fr))] gap-10">
        {disenadores.map((d) => (
          <div key={d.id_disenador} className="bg-white border border-gray-200 p-[30px] text-left">
            <h3 className="text-sm uppercase my-2.5 tracking-wide overflow-hidden text-xl mb-3.75">{d.nombre}</h3>
            <p className="text-xs text-gray-400 mb-5 overflow-hidden leading-relaxed text-gray-600">
              {d.biografia}
            </p>
            {d.web_url && (
              <a href={d.web_url} target="_blank" rel="noreferrer" className="no-underline inline-block text-black py-2.5 px-3.75 bg-gray-200 rounded font-semibold text-sm transition-colors hover:bg-yellow-400 hover:scale-105">
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
