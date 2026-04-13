import { Router, Request, Response } from 'express';
import pool from '../config/db';
import { authMiddleware, adminMiddleware, AuthRequest } from '../middleware/authMiddleware';

const router = Router();

// ── GET /api/disenadores ───────────────────────────────────
router.get('/', async (_req: Request, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query('SELECT * FROM disenadores');
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo diseñadores:', err);
    res.status(500).json({ error: 'Error obteniendo diseñadores' });
  }
});

// ── POST /api/disenadores (Requiere Admin) ─────────────────
router.post('/', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  const { nombre, biografia, web_url } = req.body;
  if (!nombre) {
    res.status(400).json({ error: 'El nombre es obligatorio' });
    return;
  }
  try {
    const [result] = await pool.query<any>(
      'INSERT INTO disenadores (nombre, biografia, web_url) VALUES (?, ?, ?)',
      [nombre, biografia || null, web_url || null]
    );
    res.status(201).json({ message: 'Diseñador creado', id_disenador: result.insertId });
  } catch (err) {
    console.error('Error creando diseñador:', err);
    res.status(500).json({ error: 'Error creando diseñador' });
  }
});

// ── PUT /api/disenadores/:id (Requiere Admin) ──────────────
router.put('/:id', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  const { nombre, biografia, web_url } = req.body;
  if (!nombre) {
    res.status(400).json({ error: 'El nombre es obligatorio' });
    return;
  }
  try {
    const [result] = await pool.query<any>(
      'UPDATE disenadores SET nombre = ?, biografia = ?, web_url = ? WHERE id_disenador = ?',
      [nombre, biografia || null, web_url || null, req.params.id]
    );
    if (result.affectedRows === 0) {
      res.status(404).json({ error: 'Diseñador no encontrado' });
      return;
    }
    res.json({ message: 'Diseñador actualizado' });
  } catch (err) {
    console.error('Error actualizando diseñador:', err);
    res.status(500).json({ error: 'Error actualizando diseñador' });
  }
});

// ── DELETE /api/disenadores/:id (Requiere Admin) ───────────
router.delete('/:id', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [result] = await pool.query<any>('DELETE FROM disenadores WHERE id_disenador = ?', [req.params.id]);
    if (result.affectedRows === 0) {
      res.status(404).json({ error: 'Diseñador no encontrado' });
      return;
    }
    res.json({ message: 'Diseñador eliminado' });
  } catch (err: any) {
    console.error('Error eliminando diseñador:', err);
    if (err.code === 'ER_ROW_IS_REFERENCED_2') {
      res.status(400).json({ error: 'No se puede eliminar un diseñador que tiene productos asignados.' });
      return;
    }
    res.status(500).json({ error: 'Error eliminando diseñador' });
  }
});

export default router;
