import { useEffect, useState } from 'react';
import AdminRoute from '../components/AdminRoute';
import apiClient from '../api/client';
import type { Producto, Categoria, Disenador } from '../types';

const AdminProductos = () => {
  const [products, setProducts] = useState<Producto[]>([]);
  const [categories, setCategories] = useState<Categoria[]>([]);
  const [designers, setDesigners] = useState<Disenador[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [productForm, setProductForm] = useState({
    nombre: '',
    descripcion: '',
    precio: 0,
    stock: 0,
    imagen_url: '',
    id_categoria: '',
    id_disenador: '',
  });
  const [selectedImage, setSelectedImage] = useState<File | null>(null);
  const [imagePreview, setImagePreview] = useState('');
  const [editingProductId, setEditingProductId] = useState<number | null>(null);
  const [editForm, setEditForm] = useState({
    nombre: '',
    descripcion: '',
    precio: 0,
    stock: 0,
    imagen_url: '',
    id_categoria: '',
    id_disenador: '',
  });
  const [editSelectedImage, setEditSelectedImage] = useState<File | null>(null);
  const [editImagePreview, setEditImagePreview] = useState('');

  const loadData = async () => {
    try {
      const [prodRes, catRes, disRes] = await Promise.all([
        apiClient.get<Producto[]>('/admin/productos'),
        apiClient.get<Categoria[]>('/admin/categorias'),
        apiClient.get<Disenador[]>('/admin/disenadores'),
      ]);

      setProducts(prodRes.data);
      setCategories(catRes.data);
      setDesigners(disRes.data);
      setError('');
    } catch (err: any) {
      console.error('Error cargando productos:', err);
      setError(err.response?.data?.error || 'No se pudo cargar información.');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  const createProduct = async () => {
    try {
      const formData = new FormData();
      formData.append('nombre', productForm.nombre);
      formData.append('descripcion', productForm.descripcion);
      formData.append('precio', productForm.precio.toString());
      formData.append('stock', productForm.stock.toString());
      if (selectedImage) {
        formData.append('imagen', selectedImage);
      } else if (productForm.imagen_url) {
        formData.append('imagen_url', productForm.imagen_url);
      }
      formData.append('id_categoria', productForm.id_categoria || '');
      formData.append('id_disenador', productForm.id_disenador || '');

      await apiClient.post('/admin/productos', formData);
      setProductForm({ nombre: '', descripcion: '', precio: 0, stock: 0, imagen_url: '', id_categoria: '', id_disenador: '' });
      setSelectedImage(null);
      setImagePreview('');
      await loadData();
    } catch (err: any) {
      console.error('Error creando producto:', err);
      setError(err.response?.data?.error || 'No se pudo crear el producto.');
    }
  };

  const deleteProduct = async (id: number) => {
    try {
      await apiClient.delete(`/admin/productos/${id}`);
      setProducts((prev) => prev.filter((item) => item.id_producto !== id));
      setError('');
    } catch (err: any) {
      console.error('Error eliminando producto:', err);
      setError(err.response?.data?.error || 'No se pudo eliminar el producto.');
    }
  };

  const startEdit = (producto: Producto) => {
    setEditingProductId(producto.id_producto);
    setEditForm({
      nombre: producto.nombre,
      descripcion: producto.descripcion,
      precio: Number(producto.precio || 0),
      stock: producto.stock,
      imagen_url: producto.imagen_url || '',
      id_categoria: producto.id_categoria?.toString() || '',
      id_disenador: producto.id_disenador?.toString() || '',
    });
    setEditSelectedImage(null);
    setEditImagePreview(producto.imagen_url ? `/img/productos/${producto.imagen_url}` : '');
  };

  const cancelEdit = () => {
    setEditingProductId(null);
    setEditForm({ nombre: '', descripcion: '', precio: 0, stock: 0, imagen_url: '', id_categoria: '', id_disenador: '' });
    setEditSelectedImage(null);
    setEditImagePreview('');
  };

  const saveProduct = async () => {
    if (editingProductId === null) return;

    try {
      const formData = new FormData();
      formData.append('nombre', editForm.nombre);
      formData.append('descripcion', editForm.descripcion);
      formData.append('precio', editForm.precio.toString());
      formData.append('stock', editForm.stock.toString());
      formData.append('id_categoria', editForm.id_categoria || '');
      formData.append('id_disenador', editForm.id_disenador || '');
      if (editSelectedImage) {
        formData.append('imagen', editSelectedImage);
      } else if (editForm.imagen_url) {
        formData.append('imagen_url', editForm.imagen_url);
      }

      await apiClient.put(`/admin/productos/${editingProductId}`, formData);
      setEditingProductId(null);
      cancelEdit();
      await loadData();
    } catch (err: any) {
      console.error('Error guardando producto:', err);
      setError(err.response?.data?.error || 'No se pudo guardar el producto.');
    }
  };

  return (
    <AdminRoute>
      <main className="pt-[120px] px-[50px] pb-[50px] bg-gray-50">
        <header className="flex justify-between items-center mb-[50px] border-b-2 border-black pb-5">
          <h2 className="m-0 text-[28px] font-black tracking-tight">Productos</h2>
          <p className="text-gray-600 text-sm">Crea y gestiona los productos de la tienda.</p>
        </header>

        {error && <p className="text-red-500 text-sm mb-2.5">{error}</p>}

        <section className="bg-white p-5 border border-gray-200 mb-10">
          <h3 className="text-lg font-bold mb-3.75">Crear producto</h3>
          <div className="flex gap-2.5 mb-2.5">
            <input value={productForm.nombre} placeholder="Nombre" onChange={(e) => setProductForm((f) => ({ ...f, nombre: e.target.value }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm" />
            <input value={productForm.precio} type="number" min="0" step="0.01" placeholder="Precio" onChange={(e) => setProductForm((f) => ({ ...f, precio: Number(e.target.value) }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm" />
            <input value={productForm.stock} type="number" min="0" placeholder="Stock" onChange={(e) => setProductForm((f) => ({ ...f, stock: Number(e.target.value) }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm" />
          </div>
          <div className="flex gap-2.5 mb-2.5">
            <input value={productForm.imagen_url} placeholder="Imagen URL" onChange={(e) => setProductForm((f) => ({ ...f, imagen_url: e.target.value }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm" />
            <input
              type="file"
              accept="image/*"
              onChange={(e) => {
                const file = e.target.files?.[0] ?? null;
                setSelectedImage(file);
                setProductForm((f) => ({ ...f, imagen_url: '' }));
                if (file) {
                  setImagePreview(URL.createObjectURL(file));
                } else {
                  setImagePreview('');
                }
              }}
              className="flex-1 text-sm"
            />
          </div>
          {imagePreview && (
            <div className="mb-4">
              <strong className="text-sm">Vista previa:</strong>
              <br />
              <img src={imagePreview} alt="Preview" className="w-[120px] h-[120px] object-cover rounded-lg mt-2" />
            </div>
          )}
          <div className="flex gap-2.5 mb-2.5">
            <select value={productForm.id_categoria} onChange={(e) => setProductForm((f) => ({ ...f, id_categoria: e.target.value }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm">
              <option value="">Categoría</option>
              {categories.map((cat) => (
                <option key={cat.id_categoria} value={cat.id_categoria}>{cat.nombre}</option>
              ))}
            </select>
            <select value={productForm.id_disenador} onChange={(e) => setProductForm((f) => ({ ...f, id_disenador: e.target.value }))} className="flex-1 p-2.5 border border-gray-300 rounded text-sm">
              <option value="">Diseñador</option>
              {designers.map((dis) => (
                <option key={dis.id_disenador} value={dis.id_disenador}>{dis.nombre}</option>
              ))}
            </select>
          </div>
          <textarea value={productForm.descripcion} placeholder="Descripción" onChange={(e) => setProductForm((f) => ({ ...f, descripcion: e.target.value }))} className="w-full p-2.5 border border-gray-300 rounded text-sm mb-2.5" rows={3} />
          <button onClick={createProduct} className="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded border-none cursor-pointer transition-opacity hover:opacity-80">Crear producto</button>
        </section>

        {loading ? (
          <p className="text-gray-500">Cargando productos...</p>
        ) : (
          <div className="border border-gray-200">
            <div className="flex bg-black text-white font-bold text-xs uppercase p-2.5 border-b border-gray-200">
              <span className="flex-1">ID</span>
              <span className="flex-1">Nombre</span>
              <span className="flex-1">Foto</span>
              <span className="flex-1">Precio</span>
              <span className="flex-1">Stock</span>
              <span className="flex-1">Categoría</span>
              <span className="flex-1">Diseñador</span>
              <span className="flex-1">Acciones</span>
            </div>
            {products.map((producto) => (
              <div key={producto.id_producto} className="flex p-2.5 border-b border-gray-100 items-center text-sm">
                <span className="flex-1">{producto.id_producto}</span>
                <span className="flex-1">
                  {editingProductId === producto.id_producto ? (
                    <input
                      value={editForm.nombre}
                      onChange={(e) => setEditForm((f) => ({ ...f, nombre: e.target.value }))}
                      className="w-full p-1 border border-gray-300 rounded text-xs"
                    />
                  ) : (
                    producto.nombre
                  )}
                </span>
                <span className="flex-1">
                  {editingProductId === producto.id_producto ? (
                    <div className="flex flex-col gap-2">
                      <input
                        value={editForm.imagen_url}
                        placeholder="URL de imagen"
                        onChange={(e) => {
                          setEditForm((f) => ({ ...f, imagen_url: e.target.value }));
                          setEditSelectedImage(null);
                          setEditImagePreview(e.target.value);
                        }}
                        className="w-full p-1 border border-gray-300 rounded text-xs"
                      />
                      <input
                        type="file"
                        accept="image/*"
                        onChange={(e) => {
                          const file = e.target.files?.[0] ?? null;
                          setEditSelectedImage(file);
                          if (file) {
                            setEditImagePreview(URL.createObjectURL(file));
                          } else {
                            setEditImagePreview(editForm.imagen_url);
                          }
                        }}
                        className="text-xs"
                      />
                      {editImagePreview && (
                        <img src={editImagePreview} alt="Preview" className="w-15 h-15 object-cover rounded" />
                      )}
                    </div>
                  ) : producto.imagen_url ? (
                    <img src={`/img/productos/${producto.imagen_url}`} alt={producto.nombre} className="w-10 h-10 object-cover rounded" />
                  ) : (
                    '-'
                  )}
                </span>
                <span className="flex-1">
                  {editingProductId === producto.id_producto ? (
                    <input
                      type="number"
                      min="0"
                      step="0.01"
                      value={editForm.precio}
                      onChange={(e) => setEditForm((f) => ({ ...f, precio: Number(e.target.value) }))}
                      className="w-full p-1 border border-gray-300 rounded text-xs"
                    />
                  ) : (
                    `${Number(producto.precio || 0).toFixed(2)}€`
                  )}
                </span>
                <span className="flex-1">
                  {editingProductId === producto.id_producto ? (
                    <input
                      type="number"
                      min="0"
                      value={editForm.stock}
                      onChange={(e) => setEditForm((f) => ({ ...f, stock: Number(e.target.value) }))}
                      className="w-full p-1 border border-gray-300 rounded text-xs"
                    />
                  ) : (
                    producto.stock
                  )}
                </span>
                <span className="flex-1">
                  {editingProductId === producto.id_producto ? (
                    <select
                      value={editForm.id_categoria}
                      onChange={(e) => setEditForm((f) => ({ ...f, id_categoria: e.target.value }))}
                      className="w-full p-1 border border-gray-300 rounded text-xs"
                    >
                      <option value="">Categoría</option>
                      {categories.map((cat) => (
                        <option key={cat.id_categoria} value={cat.id_categoria}>{cat.nombre}</option>
                      ))}
                    </select>
                  ) : (
                    producto.id_categoria || '-'
                  )}
                </span>
                <span className="flex-1">
                  {editingProductId === producto.id_producto ? (
                    <select
                      value={editForm.id_disenador}
                      onChange={(e) => setEditForm((f) => ({ ...f, id_disenador: e.target.value }))}
                      className="w-full p-1 border border-gray-300 rounded text-xs"
                    >
                      <option value="">Diseñador</option>
                      {designers.map((dis) => (
                        <option key={dis.id_disenador} value={dis.id_disenador}>{dis.nombre}</option>
                      ))}
                    </select>
                  ) : (
                    producto.id_disenador || '-'
                  )}
                </span>
                <span className="flex-1 flex gap-1">
                  {editingProductId === producto.id_producto ? (
                    <>
                      <button onClick={saveProduct} className="px-2 py-1 bg-blue-600 text-white text-xs font-bold rounded border-none cursor-pointer">Guardar</button>
                      <button onClick={cancelEdit} className="px-2 py-1 bg-gray-500 text-white text-xs font-bold rounded border-none cursor-pointer">Cancelar</button>
                    </>
                  ) : (
                    <>
                      <button onClick={() => startEdit(producto)} className="px-2 py-1 bg-gray-500 text-white text-xs font-bold rounded border-none cursor-pointer">Editar</button>
                      <button onClick={() => deleteProduct(producto.id_producto)} className="px-2 py-1 bg-red-600 text-white text-xs font-bold rounded border-none cursor-pointer">Eliminar</button>
                    </>
                  )}
                </span>
              </div>
            ))}
          </div>
        )}
      </main>
    </AdminRoute>
  );
};

export default AdminProductos;
