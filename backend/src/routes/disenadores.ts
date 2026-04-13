import { Router, Request, Response } from 'express';
import pool from '../config/db';

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

export default router;
