import { Router, Request, Response } from 'express';
import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';
import pool from '../config/db';

const router = Router();

// ── POST /api/auth/login ───────────────────────────────────
router.post('/login', async (req: Request, res: Response): Promise<void> => {
  const { email, contrasena } = req.body;

  if (!email || !contrasena) {
    res.status(400).json({ error: 'Email y contraseña requeridos' });
    return;
  }

  try {
    const [rows] = await pool.query<any[]>(
      'SELECT * FROM usuarios WHERE email = ?',
      [email]
    );
    const usuario = (rows as any[])[0];

    if (!usuario) {
      res.status(401).json({ error: 'Credenciales incorrectas' });
      return;
    }

    const passwordValida = await bcrypt.compare(contrasena, usuario.contrasena);
    if (!passwordValida) {
      res.status(401).json({ error: 'Credenciales incorrectas' });
      return;
    }

    const payload = {
      id_usuario: usuario.id_usuario,
      nombre:     usuario.nombre,
      rol:        usuario.rol,
    };

    const token = jwt.sign(
      payload,
      process.env.JWT_SECRET || 'secret',
      { expiresIn: '7d' }
    );

    res.json({
      token,
      usuario: {
        id_usuario: usuario.id_usuario,
        nombre:     usuario.nombre,
        email:      usuario.email,
        rol:        usuario.rol,
      },
    });
  } catch (err) {
    console.error('Error login:', err);
    res.status(500).json({ error: 'Error del servidor' });
  }
});

// ── POST /api/auth/registro ────────────────────────────────
router.post('/registro', async (req: Request, res: Response): Promise<void> => {
  const { nombre, email, contrasena } = req.body;

  if (!nombre || !email || !contrasena) {
    res.status(400).json({ error: 'Todos los campos son requeridos' });
    return;
  }

  try {
    const [existing] = await pool.query<any[]>(
      'SELECT email FROM usuarios WHERE email = ?',
      [email]
    );

    if ((existing as any[]).length > 0) {
      res.status(409).json({ error: 'El email ya está registrado' });
      return;
    }

    const hash = await bcrypt.hash(contrasena, 12);

    await pool.query(
      'INSERT INTO usuarios (nombre, email, contrasena, rol) VALUES (?, ?, ?, "cliente")',
      [nombre, email, hash]
    );

    res.status(201).json({ message: 'Usuario registrado correctamente' });
  } catch (err) {
    console.error('Error registro:', err);
    res.status(500).json({ error: 'Error del servidor' });
  }
});

export default router;
