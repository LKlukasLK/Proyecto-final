import { Router, Response } from 'express';
import Stripe from 'stripe';
import pool from '../config/db';
import { authMiddleware, AuthRequest } from '../middleware/authMiddleware';

const router = Router();
router.use(authMiddleware);

// ── POST /api/pagos/preparar ───────────────────────────────
router.post('/preparar', async (req: AuthRequest, res: Response): Promise<void> => {
  const { total, items } = req.body;
  const idUsuario = req.user!.id_usuario;

  if (!total || !items || items.length === 0) {
    res.status(400).json({ error: 'Total e items requeridos' });
    return;
  }

  try {
    // 1. Crear pedido en BD con estado pendiente
    const [result] = await pool.query<any>(
      `INSERT INTO pedidos (id_usuario, fecha_pedido, total, estado)
       VALUES (?, NOW(), ?, 'pendiente')`,
      [idUsuario, total]
    );
    const orderId = (result as any).insertId;

    // 2. Insertar detalles del pedido
    for (const item of items) {
      await pool.query(
        `INSERT INTO detalles_pedido (id_pedido, id_producto, cantidad, precio_unitario)
         VALUES (?, ?, ?, ?)`,
        [orderId, item.id_producto, item.cantidad, item.precio]
      );
    }

    // 3. Marcar carrito como convertido
    await pool.query(
      `UPDATE carritos SET estado = 'convertido' WHERE id_usuario = ? AND estado = 'activo'`,
      [idUsuario]
    );

    // 4. Crear sesión de Stripe
    const stripe = new Stripe(process.env.STRIPE_SECRET_KEY || '', {
      apiVersion: '2023-10-16' as any,
    });

    const baseUrl = process.env.FRONTEND_URL || 'http://localhost:5173';

    const session = await stripe.checkout.sessions.create({
      payment_method_types: ['card'],
      line_items: [
        {
          price_data: {
            currency: 'eur',
            product_data: {
              name: `Mercado Ropa – Pedido #${orderId}`,
            },
            unit_amount: Math.round(total * 100),
          },
          quantity: 1,
        },
      ],
      mode: 'payment',
      success_url: `${baseUrl}/pago-exitoso?id=${orderId}`,
      cancel_url:  `${baseUrl}/carrito`,
    });

    res.json({ url: session.url, orderId });
  } catch (err: any) {
    console.error('Error procesando pago:', err);
    res.status(500).json({ error: `Error procesando pago: ${err.message}` });
  }
});

export default router;
