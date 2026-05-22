# Mercado Ropa

Proyecto full-stack para una tienda de moda online llamada Mercado Ropa.

## Descripción

Este repositorio contiene una aplicación web de e-commerce con:

- Backend en Node.js, Express y TypeScript
- Base de datos MySQL
- Autenticación con JWT
- Pagos con Stripe
- Frontend en React, TypeScript, Vite y Tailwind CSS

## Características principales

- Registro y login de usuarios
- Catálogo de productos
- Búsqueda de productos
- Carrito de compras con cantidad e ítems
- Pago de pedido con Stripe
- Panel administrativo para gestionar usuarios, productos, diseñadores, categorías, carritos y pedidos

## Estructura del repositorio

- `backend/`: servidor REST con rutas y lógica de negocio
- `frontend/`: aplicación React y experiencia de usuario
- `docs/`: documentación técnica y guías de uso

## Inicio rápido

1. Configurar el backend:
   - Revisar `backend/.env.example`
   - Crear un archivo `backend/.env`
   - Instalar dependencias con `npm install`
   - Ejecutar `npm run dev`

2. Configurar el frontend:
   - Instalar dependencias con `npm install`
   - Ejecutar `npm run dev`

3. Abrir la aplicación:
   - Frontend: `http://localhost:5173`
   - Backend: `http://localhost:3001`

## Documentación

- `docs/INSTALLATION.md` — Guía de instalación y configuración
- `docs/USER_MANUAL.md` — Manual del usuario y flujo funcional
- `docs/API_REFERENCE.md` — Referencia de endpoints REST
- `docs/ARCHITECTURE.md` — Descripción de la arquitectura y módulos

## Notas

- La API backend expone los recursos bajo `/api`
- El frontend usa proxy Vite para enrutar `/api` a `http://localhost:3001`
- Las imágenes de productos se sirven desde `/img`
