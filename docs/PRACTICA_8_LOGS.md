# Práctica 8 – Logs de Seguridad y Auditoría

## Eventos implementados

| Evento | Canal | Nivel | Datos incluidos |
|---|---|---|---|
| `LOGIN_EXITOSO` | audit | info | usuario_id, email, ip, user_agent, url, metodo, timestamp |
| `LOGIN_FALLIDO` | security | warning | email_intento, ip, timestamp |
| `PRODUCTO_ELIMINADO` | audit | info | producto_id, producto_nombre, usuario_id, email, ip, user_agent, url, metodo, timestamp |
| `ACCESO_DENEGADO` | security | warning | usuario_id, usuario, ruta_solicitada, metodo, ip, timestamp |
| `CAMBIO_CONTRASEÑA` | audit | info | usuario_id, email, ip, user_agent, url, metodo, timestamp |

## Cómo demostrar cada evento

Todos los pasos asumen que el servidor corre con `php artisan serve` y que hay
al menos un usuario con credenciales `test@example.com` / `password` y un
producto en la base de datos.

### 1. LOGIN_EXITOSO

1. Ir a `http://localhost:8000/login`
2. Ingresar email `test@example.com` y contraseña `password`
3. Hacer clic en "Iniciar sesión"
4. El evento queda registrado en `storage/logs/audit/audit-YYYY-MM-DD.log`

### 2. LOGIN_FALLIDO

1. Ir a `http://localhost:8000/login`
2. Ingresar cualquier email y una contraseña incorrecta
3. Hacer clic en "Iniciar sesión"
4. El evento queda registrado en `storage/logs/security/security-YYYY-MM-DD.log`

### 3. PRODUCTO_ELIMINADO

1. Iniciar sesión como `test@example.com` / `password`
2. Ir a `http://localhost:8000/productos` y anotar el ID de algún producto
3. Ejecutar desde la terminal:
   ```bash
   curl -X POST http://localhost:8000/productos/1/eliminar \
     -b <archivo_de_cookies_de_sesion>
   ```
   O generar un formulario POST con CSRF token desde una vista Blade.
4. El evento queda registrado en `storage/logs/audit/audit-YYYY-MM-DD.log`

### 4. ACCESO_DENEGADO

1. Iniciar sesión como `test@example.com` / `password` (usuario **sin** rol admin)
2. Ir a `http://localhost:8000/admin/panel-restringido`
3. El middleware `EsAdmin` detecta que el usuario no es admin, registra el
   evento y redirige a `/perfil` con un mensaje de error.
4. El evento queda registrado en `storage/logs/security/security-YYYY-MM-DD.log`

### 5. CAMBIO_CONTRASEÑA

1. Iniciar sesión como `test@example.com` / `password`
2. Ir a `http://localhost:8000/perfil`
3. En la sección "Actualizar contraseña", ingresar:
   - Contraseña actual: `password`
   - Nueva contraseña: `newpassword`
   - Confirmar contraseña: `newpassword`
4. Hacer clic en "Guardar"
5. El evento queda registrado en `storage/logs/audit/audit-YYYY-MM-DD.log`

> **Nota:** Después del cambio de contraseña los siguientes inicios de sesión
> deben usar la nueva contraseña (`newpassword`).

## Dónde encontrar los logs

| Canal | Archivo | Retención |
|---|---|---|
| `audit` | `storage/logs/audit/audit-YYYY-MM-DD.log` | 365 días |
| `security` | `storage/logs/security/security-YYYY-MM-DD.log` | 90 días |
| `critical` | `storage/logs/critical.log` | Indefinido (archivo único) |

Todos los logs se escriben en formato JSON para facilitar su procesamiento
automático y su envío a sistemas centralizados como ELK Stack.

## Sobre ELK Stack (Elasticsearch + Logstash + Kibana)

En un entorno de producción con Docker, el siguiente paso natural sería
centralizar estos logs utilizando ELK Stack:

- **Elasticsearch** — almacena e indexa los logs para búsquedas rápidas.
- **Logstash** — ingiere los archivos JSON de `storage/logs/` y los envía a
  Elasticsearch.
- **Kibana** — permite visualizar, filtrar y crear dashboards sobre los
  eventos de seguridad y auditoría en tiempo real.

Esta configuración queda fuera del alcance de la práctica local, pero sería
el paso siguiente para un despliegue en producción.

## Reflexión

Separar los logs de auditoría (acciones exitosas de usuarios) de los logs de
seguridad (eventos anómalos o denegados) permite aplicar políticas de
retención distintas y facilita la revisión rápida de incidentes sin el ruido
de la actividad normal. OWASP recomienda que todo log de seguridad incluya:
timestamp en UTC, IP del cliente, usuario autenticado (si existe), la acción
realizada, el recurso afectado y el resultado (éxito/fallo). Nuestra
implementación cumple con esta recomendación al incluir todos estos campos
en los eventos registrados.
