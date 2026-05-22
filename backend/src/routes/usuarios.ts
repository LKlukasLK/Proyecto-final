import { Router, Request, Response } from 'express';
import pool from '../config/db';
import { authMiddleware, adminMiddleware, AuthRequest } from '../middleware/authMiddleware';

const router = Router();

// ── GET /api/usuarios (Requiere Admin) ─────────────────────
router.get('/', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query('SELECT id_usuario, nombre, email, rol, fecha_registro FROM usuarios ORDER BY id_usuario DESC');
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo usuarios:', err);
    res.status(500).json({ error: 'Error obteniendo usuarios' });
  }
});

// ── PUT /api/usuarios/:id/rol (Requiere Admin) ─────────────
router.put('/:id/rol', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  const { rol } = req.body;
  if (rol !== 'admin' && rol !== 'cliente') {
    res.status(400).json({ error: 'Rol inválido' });
    return;
  }

  try {
    const [result] = await pool.query<any>(
      'UPDATE usuarios SET rol = ? WHERE id_usuario = ?',
      [rol, req.params.id]
    );
    if (result.affectedRows === 0) {
      res.status(404).json({ error: 'Usuario no encontrado' });
      return;
    }
    res.json({ message: 'Rol de usuario actualizado exitosamente' });
  } catch (err) {
    console.error('Error actualizando rol de usuario:', err);
    res.status(500).json({ error: 'Error actualizando rol de usuario' });
  }
});

// ── DELETE /api/usuarios/:id (Requiere Admin) ─────────────
router.delete('/:id', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [result] = await pool.query<any>(
      'DELETE FROM usuarios WHERE id_usuario = ?',
      [req.params.id]
    );
    if (result.affectedRows === 0) {
      res.status(404).json({ error: 'Usuario no encontrado' });
      return;
    }
    res.json({ message: 'Usuario eliminado correctamente' });
  } catch (err) {
    console.error('Error eliminando usuario:', err);
    res.status(500).json({ error: 'Error eliminando usuario' });
  }
});

export default router;
