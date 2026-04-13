import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import path from 'path';

import productosRouter from './routes/productos';
import authRouter from './routes/auth';
import carritoRouter from './routes/carrito';
import disenadoresRouter from './routes/disenadores';
import pagosRouter from './routes/pagos';

dotenv.config();

const app = express();
const PORT = process.env.PORT || 3001;

// ── Middlewares ────────────────────────────────────────────
app.use(cors({
  origin: process.env.FRONTEND_URL || 'http://localhost:5173',
  credentials: true,
}));
app.use(express.json());

// ── Archivos estáticos (imágenes de productos) ───────────────
const imgPath = path.join(__dirname, '../../public/img');
app.use('/img', express.static(imgPath));

// ── Rutas API ──────────────────────────────────────────────
app.use('/api/productos', productosRouter);
app.use('/api/auth', authRouter);
app.use('/api/carrito', carritoRouter);
app.use('/api/disenadores', disenadoresRouter);
app.use('/api/pagos', pagosRouter);

// ── Health check ───────────────────────────────────────────
app.get('/api/health', (_req, res) => {
  res.json({ status: 'ok', timestamp: new Date().toISOString() });
});

// ── Inicio ─────────────────────────────────────────────────
app.listen(PORT, () => {
  console.log(`🚀 Backend corriendo en http://localhost:${PORT}`);
});
