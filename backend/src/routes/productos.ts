import { Router, Request, Response } from 'express';
import pool from '../config/db';

const router = Router();

// ── GET /api/productos ─────────────────────────────────────
router.get('/', async (req: Request, res: Response): Promise<void> => {
  try {
    const q = req.query.q as string | undefined;
    let rows;

    if (q && q.trim() !== '') {
      [rows] = await pool.query(
        'SELECT * FROM productos WHERE nombre LIKE ? OR descripcion LIKE ?',
        [`%${q}%`, `%${q}%`]
      );
    } else {
      [rows] = await pool.query('SELECT * FROM productos');
    }

    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo productos:', err);
    res.status(500).json({ error: 'Error obteniendo productos' });
  }
});

// ── GET /api/productos/:id ─────────────────────────────────
router.get('/:id', async (req: Request, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query<any[]>(
      'SELECT * FROM productos WHERE id_producto = ?',
      [req.params.id]
    );

    const producto = (rows as any[])[0];
    if (!producto) {
      res.status(404).json({ error: 'Producto no encontrado' });
      return;
    }

    res.json(producto);
  } catch (err) {
    console.error('Error obteniendo producto:', err);
    res.status(500).json({ error: 'Error obteniendo producto' });
  }
});

export default router;
