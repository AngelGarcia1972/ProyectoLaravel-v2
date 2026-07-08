# Práctica: CSRF & XSS — Demostración en Laravel

## Requisitos

```bash
php artisan serve
npm run dev
```

Abrir `http://localhost:8000/comentarios` en el navegador.

---

## 1. XSS Almacenado (sin protección) — ruta aislada de prueba

**URL:** `http://localhost:8000/comentarios/sin-csrf`

**Qué hacer:**
1. Rellenar el formulario con un payload como `<script>alert('XSS')</script>` en el campo **Contenido**.
2. Hacer clic en "Enviar (demo sin protección)".
3. Serás redirigido a `GET /comentarios/sin-csrf` (que ahora también lista los comentarios como la ruta normal).

**Qué observar:**
- El comentario se almacena **sin aplicar `strip_tags`** porque el método `storeSinProteccion()` omite esa sanitización.
- Al renderizar la vista con `{!! $comentario->contenido !!}`, el `<script>` se ejecuta (o se renderiza como HTML).
- Esto demuestra **Stored XSS**: el atacante persiste código malicioso en la base de datos y se ejecuta al cargar la página.

> **Nota:** Esta ruta está aislada y claramente marcada como DEMO ONLY. Nunca existiría en producción.

---

## 2. Blade `{{ }}` neutralizando el ataque XSS

**URL:** `http://localhost:8000/comentarios`

**Qué observar:**
- Cada comentario se muestra en **dos columnas**:
  - **Columna verde (Seguro):** usa `{{ $comentario->contenido }}`. Blade escapa el HTML → el `<script>` se muestra como texto, no se ejecuta.
  - **Columna roja (Peligroso):** usa `{!! $comentario->contenido !!}`. NO escapa → el `<script>` se ejecuta y el `<b>` se renderiza como negrita.

### Comportamiento esperado (con el payload de prueba incluido en el seeder):

| Columna Segura (`{{ }}`) | Columna Peligrosa (`{!! !!}`) |
|---|---|
| El texto `<script>alert('XSS')</script>` se ve literalmente como código | Aparece un `alert()` o el script se ejecuta |
| `<b>texto</b>` se ve como texto plano con etiquetas visibles | `<b>texto</b>` se renderiza en **negrita** |

---

## 3. Envío sin token CSRF → Error 419

**URL:** `http://localhost:8000/comentarios/crear`

**Qué hacer:**
1. Usar el **segundo formulario** (fondo rojo, etiquetado "Formulario VULNERABLE (sin @csrf)").
2. Rellenar los campos y enviar.

**Qué observar:**
- Laravel devuelve un error **419 Page Expired**.
- Esto ocurre porque el middleware `VerifyCsrfToken` (activo por defecto en el grupo `web`) detecta que la petición POST no incluye el token CSRF y la rechaza antes siquiera de llegar al controlador.

**Alternativa con el primer formulario (fondo verde):**
- Incluye `@csrf` → el token se envía → la petición se procesa correctamente.

---

## 4. Cabecera CSP en DevTools

**URL:** Cualquier ruta del sitio, por ejemplo `http://localhost:8000/comentarios`

**Qué hacer:**
1. Abrir DevTools del navegador (F12).
2. Ir a la pestaña **Network** (Red).
3. Recargar la página y seleccionar el documento (la primera petición).
4. Buscar la cabecera **Content-Security-Policy** en la sección **Response Headers**.

**Qué observar:**

```
Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-{token}'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self'; connect-src 'self'; frame-ancestors 'none'
```

- `default-src 'self'` → solo se permiten recursos del mismo origen.
- `script-src 'self' 'nonce-{token}'` → los scripts deben tener un nonce válido o ser del mismo origen. Los `<script>` inline sin nonce (como los del XSS) serán bloqueados por el navegador.
- `frame-ancestors 'none'` → protege contra clickjacking.

> **Nota:** El CSP middleware se aplica a todas las rutas del grupo `web` (configurado en `bootstrap/app.php`).

---

## Resumen de rutas

| Método | URL | Nombre | Propósito |
|---|---|---|---|
| GET | `/comentarios` | `comentarios.index` | Listar comentarios (columnas segura vs peligrosa) |
| GET | `/comentarios/crear` | `comentarios.create` | Formulario con y sin @csrf (para probar 419) |
| POST | `/comentarios` | `comentarios.store` | Guardar con CSRF + strip_tags |
| GET | `/comentarios/sin-csrf` | `comentarios.sin-csrf` | Formulario vulnerable (sin CSRF, sin strip_tags) |
| POST | `/comentarios/sin-csrf` | `comentarios.store.sinCsrf` | Guardar vulnerable (excluido de CSRF, sin strip_tags) |

---

## Archivos clave

| Archivo | Propósito |
|---|---|
| `app/Http/Controllers/ComentarioController.php` | Lógica: store (protegido), storeSinProteccion (demo) |
| `app/Http/Middleware/ContentSecurityPolicy.php` | Middleware CSP |
| `bootstrap/app.php` | Configuración de middleware (CSRF, CSP, exclusión) |
| `routes/web.php` | Definición de rutas del demo |
| `resources/views/comentarios/` | Vistas Blade del demo |
| `database/migrations/..._create_comentarios_table.php` | Migración |
| `database/seeders/ComentarioSeeder.php` | Datos de prueba con payload XSS |
