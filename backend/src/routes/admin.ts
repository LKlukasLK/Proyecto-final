import { Router, Response } from 'express';
import multer from 'multer';
import path from 'path';
import fs from 'fs';
import pool from '../config/db';
import { authMiddleware, adminMiddleware, AuthRequest } from '../middleware/authMiddleware';

const uploadDirectory = path.join(__dirname, '../../public/img/productos');
if (!fs.existsSync(uploadDirectory)) {
  fs.mkdirSync(uploadDirectory, { recursive: true });
}

const storage = multer.diskStorage({
  destination: (_req, _file, cb) => {
    cb(null, uploadDirectory);
  },
  filename: (_req, file, cb) => {
    const uniqueSuffix = `${Date.now()}-${Math.round(Math.random() * 1e9)}`;
    const ext = path.extname(file.originalname).toLowerCase();
    cb(null, `producto-${uniqueSuffix}${ext}`);
  },
});

const upload = multer({ storage });

const router = Router();
router.use(authMiddleware, adminMiddleware);

// Usuarios
router.get('/usuarios', async (_req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query(`SELECT id_usuario, nombre, email, rol FROM usuarios`);
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo usuarios admin:', err);
    res.status(500).json({ error: 'Error al cargar usuarios' });
  }
});

router.put('/usuarios/:id', async (req: AuthRequest, res: Response): Promise<void> => {
  const { rol } = req.body;
  if (!rol || !['admin', 'cliente'].includes(rol)) {
    res.status(400).json({ error: 'Rol inválido' });
    return;
  }

  try {
    await pool.query('UPDATE usuarios SET rol = ? WHERE id_usuario = ?', [rol, req.params.id]);
    res.json({ message: 'Rol actualizado' });
  } catch (err) {
    console.error('Error actualizando rol de usuario:', err);
    res.status(500).json({ error: 'Error actualizando rol de usuario' });
  }
});

router.delete('/usuarios/:id', async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    await pool.query('DELETE FROM usuarios WHERE id_usuario = ?', [req.params.id]);
    res.json({ message: 'Usuario eliminado' });
  } catch (err) {
    console.error('Error eliminando usuario:', err);
    res.status(500).json({ error: 'Error eliminando usuario' });
  }
});

// Carritos
router.get('/carritos', async (_req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query(
      `SELECT c.id_carrito, c.id_usuario, u.nombre AS cliente, u.email, c.fecha_creacion, c.estado,
              COALESCE(SUM(dc.cantidad), 0) AS total_items
       FROM carritos c
       JOIN usuarios u ON c.id_usuario = u.id_usuario
       LEFT JOIN detalles_carrito dc ON dc.id_carrito = c.id_carrito
       GROUP BY c.id_carrito
       ORDER BY c.fecha_creacion DESC`
    );
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo carritos admin:', err);
    res.status(500).json({ error: 'Error al cargar carritos' });
  }
});

router.get('/carritos/:id/items', async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query(
      `SELECT p.id_producto, p.nombre, p.precio, p.imagen_url, dc.cantidad
       FROM detalles_carrito dc
       JOIN productos p ON dc.id_producto = p.id_producto
       WHERE dc.id_carrito = ?`,
      [req.params.id]
    );
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo items de carrito:', err);
    res.status(500).json({ error: 'Error al cargar items del carrito' });
  }
});

router.put('/carritos/:id', async (req: AuthRequest, res: Response): Promise<void> => {
  const { estado } = req.body;
  if (!estado || !['activo', 'abandonado', 'convertido'].includes(estado)) {
    res.status(400).json({ error: 'Estado de carrito inválido' });
    return;
  }

  try {
    await pool.query('UPDATE carritos SET estado = ? WHERE id_carrito = ?', [estado, req.params.id]);
    res.json({ message: 'Estado de carrito actualizado' });
  } catch (err) {
    console.error('Error actualizando estado de carrito:', err);
    res.status(500).json({ error: 'Error actualizando carrito' });
  }
});

// Productos
router.get('/productos', async (_req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query('SELECT * FROM productos');
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo productos admin:', err);
    res.status(500).json({ error: 'Error al cargar productos' });
  }
});

router.post('/productos', upload.single('imagen'), async (req: AuthRequest, res: Response): Promise<void> => {
  const { nombre, descripcion, precio, stock, imagen_url, id_categoria, id_disenador } = req.body;
  const precioNumber = Number(precio);
  const stockNumber = Number(stock);
  const categoriaId = id_categoria ? Number(id_categoria) : null;
  const disenadorId = id_disenador ? Number(id_disenador) : null;

  if (!nombre || Number.isNaN(precioNumber) || Number.isNaN(stockNumber)) {
    res.status(400).json({ error: 'Nombre, precio y stock válidos son requeridos' });
    return;
  }

  const imageUrl = req.file ? req.file.filename : (imagen_url || null);

  try {
    const [result] = await pool.query(
      `INSERT INTO productos (nombre, descripcion, precio, stock, imagen_url, id_categoria, id_disenador)
       VALUES (?, ?, ?, ?, ?, ?, ?)`,
      [nombre, descripcion || '', precioNumber, stockNumber, imageUrl, categoriaId, disenadorId]
    );
    res.status(201).json({ id_producto: (result as any).insertId });
  } catch (err) {
    console.error('Error creando producto:', err);
    res.status(500).json({ error: 'Error creando producto' });
  }
});

router.put('/productos/:id', upload.single('imagen'), async (req: AuthRequest, res: Response): Promise<void> => {
  const { nombre, descripcion, precio, stock, imagen_url, id_categoria, id_disenador } = req.body;
  const precioNumber = Number(precio);
  const stockNumber = Number(stock);
  const categoriaId = id_categoria ? Number(id_categoria) : null;
  const disenadorId = id_disenador ? Number(id_disenador) : null;

  if (!nombre || Number.isNaN(precioNumber) || Number.isNaN(stockNumber)) {
    res.status(400).json({ error: 'Nombre, precio y stock válidos son requeridos' });
    return;
  }

  try {
    const [existingRows] = await pool.query<any[]>(
      'SELECT imagen_url FROM productos WHERE id_producto = ?',
      [req.params.id]
    );
    const existing = (existingRows as any[])[0];
    if (!existing) {
      res.status(404).json({ error: 'Producto no encontrado' });
      return;
    }

    const imageUrl = req.file
      ? req.file.filename
      : (typeof imagen_url !== 'undefined' ? imagen_url : existing.imagen_url);

    await pool.query(
      `UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen_url = ?, id_categoria = ?, id_disenador = ?
       WHERE id_producto = ?`,
      [nombre, descripcion || '', precioNumber, stockNumber, imageUrl, categoriaId, disenadorId, req.params.id]
    );
    res.json({ message: 'Producto actualizado' });
  } catch (err) {
    console.error('Error actualizando producto:', err);
    res.status(500).json({ error: 'Error actualizando producto' });
  }
});

router.delete('/productos/:id', async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    await pool.query('DELETE FROM productos WHERE id_producto = ?', [req.params.id]);
    res.json({ message: 'Producto eliminado' });
  } catch (err) {
    console.error('Error eliminando producto:', err);
    res.status(500).json({ error: 'Error eliminando producto' });
  }
});

// Diseñadores
router.get('/disenadores', async (_req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query('SELECT * FROM disenadores');
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo diseñadores admin:', err);
    res.status(500).json({ error: 'Error al cargar diseñadores' });
  }
});

router.post('/disenadores', async (req: AuthRequest, res: Response): Promise<void> => {
  const { nombre, biografia, web_url } = req.body;
  if (!nombre) {
    res.status(400).json({ error: 'Nombre es requerido' });
    return;
  }

  try {
    const [result] = await pool.query(
      'INSERT INTO disenadores (nombre, biografia, web_url) VALUES (?, ?, ?)',
      [nombre, biografia || '', web_url || '']
    );
    res.status(201).json({ id_disenador: (result as any).insertId });
  } catch (err) {
    console.error('Error creando diseñador:', err);
    res.status(500).json({ error: 'Error creando diseñador' });
  }
});

router.put('/disenadores/:id', async (req: AuthRequest, res: Response): Promise<void> => {
  const { nombre, biografia, web_url } = req.body;

  try {
    await pool.query(
      'UPDATE disenadores SET nombre = ?, biografia = ?, web_url = ? WHERE id_disenador = ?',
      [nombre, biografia || '', web_url || '', req.params.id]
    );
    res.json({ message: 'Diseñador actualizado' });
  } catch (err) {
    console.error('Error actualizando diseñador:', err);
    res.status(500).json({ error: 'Error actualizando diseñador' });
  }
});

router.delete('/disenadores/:id', async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    await pool.query('DELETE FROM disenadores WHERE id_disenador = ?', [req.params.id]);
    res.json({ message: 'Diseñador eliminado' });
  } catch (err) {
    console.error('Error eliminando diseñador:', err);
    res.status(500).json({ error: 'Error eliminando diseñador' });
  }
});

// Categorías
router.get('/categorias', async (_req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query('SELECT * FROM categorias');
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo categorías admin:', err);
    res.status(500).json({ error: 'Error al cargar categorías' });
  }
});

router.post('/categorias', async (req: AuthRequest, res: Response): Promise<void> => {
  const { nombre, descripcion } = req.body;
  if (!nombre) {
    res.status(400).json({ error: 'Nombre es requerido' });
    return;
  }

  try {
    const [result] = await pool.query(
      'INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)',
      [nombre, descripcion || '']
    );
    res.status(201).json({ id_categoria: (result as any).insertId });
  } catch (err) {
    console.error('Error creando categoría:', err);
    res.status(500).json({ error: 'Error creando categoría' });
  }
});

router.put('/categorias/:id', async (req: AuthRequest, res: Response): Promise<void> => {
  const { nombre, descripcion } = req.body;

  try {
    await pool.query(
      'UPDATE categorias SET nombre = ?, descripcion = ? WHERE id_categoria = ?',
      [nombre, descripcion || '', req.params.id]
    );
    res.json({ message: 'Categoría actualizada' });
  } catch (err) {
    console.error('Error actualizando categoría:', err);
    res.status(500).json({ error: 'Error actualizando categoría' });
  }
});

router.delete('/categorias/:id', async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    await pool.query('DELETE FROM categorias WHERE id_categoria = ?', [req.params.id]);
    res.json({ message: 'Categoría eliminada' });
  } catch (err) {
    console.error('Error eliminando categoría:', err);
    res.status(500).json({ error: 'Error eliminando categoría' });
  }
});

// Pedidos
router.get('/pedidos', async (_req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query(
      `SELECT p.id_pedido, p.id_usuario, u.nombre AS cliente, p.fecha_pedido, p.total, p.estado
       FROM pedidos p
       JOIN usuarios u ON p.id_usuario = u.id_usuario
       ORDER BY p.fecha_pedido DESC`
    );
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo pedidos admin:', err);
    res.status(500).json({ error: 'Error al cargar pedidos' });
  }
});

router.get('/pedidos/:id/detalles', async (req: AuthRequest, res: Response): Promise<void> => {
  try {
    const [rows] = await pool.query(
      `SELECT dp.id_detalle, dp.id_producto, p.nombre, dp.cantidad, dp.precio_unitario
       FROM detalles_pedido dp
       JOIN productos p ON dp.id_producto = p.id_producto
       WHERE dp.id_pedido = ?`,
      [req.params.id]
    );
    res.json(rows);
  } catch (err) {
    console.error('Error obteniendo detalles de pedido:', err);
    res.status(500).json({ error: 'Error al cargar detalles del pedido' });
  }
});

router.put('/pedidos/:id', async (req: AuthRequest, res: Response): Promise<void> => {
  const { estado } = req.body;
  if (!estado) {
    res.status(400).json({ error: 'Estado requerido' });
    return;
  }

  try {
    await pool.query('UPDATE pedidos SET estado = ? WHERE id_pedido = ?', [estado, req.params.id]);
    res.json({ message: 'Pedido actualizado' });
  } catch (err) {
    console.error('Error actualizando pedido:', err);
    res.status(500).json({ error: 'Error actualizando pedido' });
  }
});

export default router;
