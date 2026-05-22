import { Router, Request, Response } from 'express';
import pool from '../config/db';
import { authMiddleware, adminMiddleware, AuthRequest } from '../middleware/authMiddleware';

const router = Router();

// ── GET /api/pedidos (Requiere Admin) ──────────────────────
router.get('/', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    // Left join for user info to show which user made the order
    const [rows] = await pool.query(`
      SELECT p.id_pedido, p.total, p.estado, p.fecha_pedido, u.nombre as cliente, u.email
      FROM pedidos p
      LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
      ORDER BY p.id_pedido DESC
    `);
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo pedidos:', err);
    res.status(500).json({ error: 'Error obteniendo pedidos' });
  }
});

// ── PUT /api/pedidos/:id/estado (Requiere Admin) ────────────
router.put('/:id/estado', authMiddleware, adminMiddleware, async (req: AuthRequest, res: Response): Promise<void> => {
  const { estado } = req.body;
  const validEstados = ['pendiente', 'pagado', 'enviado', 'cancelado', 'completado'];
  if (!validEstados.includes(estado)) {
    res.status(400).json({ error: 'Estado inválido' });
    return;
  }

  try {
    const [result] = await pool.query<any>(
      'UPDATE pedidos SET estado = ? WHERE id_pedido = ?',
      [estado, req.params.id]
    );

    if (result.affectedRows === 0) {
      res.status(404).json({ error: 'Pedido no encontrado' });
      return;
    }
    res.json({ message: 'Estado del pedido actualizado' });
  } catch (err) {
    console.error('Error actualizando pedido:', err);
    res.status(500).json({ error: 'Error actualizando pedido' });
  }
});

export default router;
