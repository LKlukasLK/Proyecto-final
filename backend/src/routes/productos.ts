import { Router, Request, Response } from 'express';
import pool from '../config/db';
import { authMiddleware, adminMiddleware, AuthRequest } from '../middleware/authMiddleware';

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

// ── POST /api/productos (Requiere Admin) ───────────────────
router.post('/', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  const { nombre, descripcion, precio, stock, imagen_url, id_categoria, id_disenador } = req.body;
  if (!nombre || precio === undefined) {
    res.status(400).json({ error: 'Nombre y precio son obligatorios' });
    return;
  }

  try {
    const [result] = await pool.query<any>(
      'INSERT INTO productos (nombre, descripcion, precio, stock, imagen_url, id_categoria, id_disenador) VALUES (?, ?, ?, ?, ?, ?, ?)',
      [nombre, descripcion || '', precio, stock || 0, imagen_url || null, id_categoria || 1, id_disenador || null]
    );
    res.status(201).json({ message: 'Producto creado', id_producto: result.insertId });
  } catch (err) {
    console.error('Error creando producto:', err);
    res.status(500).json({ error: 'Error creando producto' });
  }
});

// ── PUT /api/productos/:id (Requiere Admin) ─────────────────
router.put('/:id', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  const { nombre, descripcion, precio, stock, imagen_url, id_categoria, id_disenador } = req.body;
  
  try {
    const [result] = await pool.query<any>(
      'UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, imagen_url=?, id_categoria=?, id_disenador=? WHERE id_producto=?',
      [nombre, descripcion, precio, stock, imagen_url, id_categoria, id_disenador, req.params.id]
    );

    if (result.affectedRows === 0) {
      res.status(404).json({ error: 'Producto no encontrado' });
      return;
    }
    res.json({ message: 'Producto actualizado' });
  } catch (err) {
    console.error('Error actualizando producto:', err);
    res.status(500).json({ error: 'Error actualizando producto' });
  }
});

// ── DELETE /api/productos/:id (Requiere Admin) ───────────────
router.delete('/:id', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [result] = await pool.query<any>(
      'DELETE FROM productos WHERE id_producto=?',
      [req.params.id]
    );
    if (result.affectedRows === 0) {
      res.status(404).json({ error: 'Producto no encontrado' });
      return;
    }
    res.json({ message: 'Producto eliminado' });
  } catch (err) {
    console.error('Error eliminando producto:', err);
    res.status(500).json({ error: 'Error eliminando producto' });
  }
});

export default router;
