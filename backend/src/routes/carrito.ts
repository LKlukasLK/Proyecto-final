import { Router, Response } from 'express';
import pool from '../config/db';
import { authMiddleware, AuthRequest } from '../middleware/authMiddleware';

const router = Router();
router.use(authMiddleware);

// ── GET /api/carrito ───────────────────────────────────────
router.get('/', async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    const idUsuario = req.user!.id_usuario;

    const [rows] = await pool.query(
      `SELECT p.id_producto, p.nombre, p.precio, p.imagen_url, dc.cantidad
       FROM productos p
       JOIN detalles_carrito dc ON p.id_producto = dc.id_producto
       JOIN carritos c ON dc.id_carrito = c.id_carrito
       WHERE c.id_usuario = ? AND c.estado = 'activo'`,
      [idUsuario]
    );

    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo carrito:', err);
    res.status(500).json({ error: 'Error obteniendo carrito' });
  }
});

// ── POST /api/carrito/agregar ──────────────────────────────
router.post('/agregar', async (req: AuthRequest, res: Response): Promise<void> => {
  const { id_producto } = req.body;
  const idUsuario = req.user!.id_usuario;

  if (!id_producto) {
    res.status(400).json({ error: 'id_producto requerido' });
    return;
  }

  try {
    // Insertar carrito si no existe
    await pool.query(
      `INSERT IGNORE INTO carritos (id_usuario, estado) VALUES (?, 'activo')`,
      [idUsuario]
    );

    // Obtener id del carrito activo
    const [carritoRows] = await pool.query<any[]>(
      `SELECT id_carrito FROM carritos WHERE id_usuario = ? AND estado = 'activo'`,
      [idUsuario]
    );
    const idCarrito = (carritoRows as any[])[0]?.id_carrito;

    if (!idCarrito) {
      res.status(500).json({ error: 'Error creando carrito' });
      return;
    }

    // Añadir producto o incrementar cantidad
    await pool.query(
      `INSERT INTO detalles_carrito (id_carrito, id_producto, cantidad)
       VALUES (?, ?, 1)
       ON DUPLICATE KEY UPDATE cantidad = cantidad + 1`,
      [idCarrito, id_producto]
    );

    res.json({ message: 'Producto añadido al carrito' });
  } catch (err) {
    console.error('Error añadiendo al carrito:', err);
    res.status(500).json({ error: 'Error añadiendo producto' });
  }
});

// ── DELETE /api/carrito/eliminar/:id_producto ──────────────
router.delete('/eliminar/:id_producto', async (req: AuthRequest, res: Response): Promise<void> => {
  const idUsuario = req.user!.id_usuario;
  const { id_producto } = req.params;

  try {
    await pool.query(
      `DELETE dc FROM detalles_carrito dc
       JOIN carritos c ON dc.id_carrito = c.id_carrito
       WHERE c.id_usuario = ? AND dc.id_producto = ?`,
      [idUsuario, id_producto]
    );

    res.json({ message: 'Producto eliminado del carrito' });
  } catch (err) {
    console.error('Error eliminando del carrito:', err);
    res.status(500).json({ error: 'Error eliminando producto' });
  }
});

export default router;
