# Práctica 6: Autenticación Multifactor (MFA) con TOTP

## Cómo demostrar

Sigue estos pasos en orden para demostrar el flujo completo de 2FA:

### a) Habilitar 2FA desde el perfil y ver el código QR

1. Inicia sesión en `/login` con un usuario existente.
2. Navega a `/perfil`.
3. Haz clic en **"Habilitar 2FA"**.
4. Se te pedirá confirmar tu contraseña (medida de seguridad). Ingrésala.
5. Serás redirigido a `/perfil/2fa/qr` donde verás:
   - Un código QR generado automáticamente.
   - La clave secreta en texto plano como respaldo.

### b) Escanear el QR con Google Authenticator o Authy

1. Abre Google Authenticator, Authy o Microsoft Authenticator en tu celular.
2. Toca **"Agregar cuenta"** → **"Escanear código QR"**.
3. Escanea el código QR mostrado en la pantalla.
4. La aplicación agregará la cuenta automáticamente y comenzará a generar códigos de 6 dígitos que cambian cada 30 segundos.

### c) Confirmar 2FA ingresando el primer código TOTP

1. En la misma página `/perfil/2fa/qr`, ingresa el código de 6 dígitos que muestra tu aplicación autenticadora.
2. Haz clic en **"Confirmar"**.
3. Si el código es válido, serás redirigido a `/perfil` y verás el mensaje "2FA ha sido confirmada."
4. La sección de 2FA ahora mostrará **"Activado"** con la fecha de confirmación.

### d) Cerrar sesión y volver a iniciar para ver el desafío 2FA

1. Haz clic en **"Cerrar sesión"** en el menú de navegación.
2. Ve a `/login` e ingresa tus credenciales (email y contraseña).
3. Después de enviar el formulario, aparecerá la pantalla **"Autenticación de dos factores"** en `/two-factor-challenge`.
4. Abre tu aplicación autenticadora, obtén el código de 6 dígitos actual e ingrésalo.
5. Haz clic en **"Verificar"**.
6. Accederás a `/perfil` correctamente. ¡El 2FA está funcionando!

### e) Deshabilitar 2FA usando un código de recuperación

1. Estando en `/perfil`, haz clic en **"Deshabilitar 2FA"**.
   - Nota: Se te pedirá confirmar la acción.

Si no tienes acceso a tu aplicación autenticadora, en la pantalla de desafío 2FA (`/two-factor-challenge`):

1. Haz clic en **"Usar código de recuperación"**.
2. Ingresa uno de los códigos de recuperación (formato `XXXXX-XXXXX`).
3. Haz clic en **"Verificar"**.
4. Accederás a tu cuenta. Luego puedes deshabilitar 2FA desde el perfil.

> **Importante:** Los códigos de recuperación se generan automáticamente al habilitar 2FA. Puedes verlos en `/user/two-factor-recovery-codes` (ruta de Fortify). Guárdalos en un lugar seguro.

---

## Categorías de factores MFA

La autenticación multifactor se basa en tres categorías de factores:

- **Algo que sabes (conocimiento):** Una contraseña, PIN o respuesta a una pregunta secreta. Es el factor más común pero también el más vulnerable a ataques de phishing, fuerza bruta y filtración de datos.
- **Algo que tienes (posesión):** Un dispositivo físico como un smartphone, una llave de seguridad USB o una tarjeta inteligente. En TOTP, el teléfono actúa como este factor, generando códigos temporales que solo quien posee el dispositivo puede producir.
- **Algo que eres (inherencia):** Características biométricas como huella dactilar, reconocimiento facial o escaneo de iris.

TOTP (Time-based One-Time Password) fortalece la seguridad porque incluso si un atacante obtiene tu contraseña mediante phishing o una filtración de datos, no podrá acceder a tu cuenta sin el código de 6 dígitos que cambia cada 30 segundos y que solo genera tu dispositivo físico. Esto hace que el costo de un ataque exitoso sea significativamente mayor, ya que el atacante necesitaría tanto la contraseña como acceso físico a tu teléfono.

## Rutas registradas

```bash
# 2FA challenge (Fortify)
POST /two-factor-challenge

# Habilitar/deshabilitar 2FA (Fortify)
POST   /user/two-factor-authentication
DELETE /user/two-factor-authentication
POST   /user/confirmed-two-factor-authentication
GET    /user/two-factor-qr-code

# Gestión desde el perfil (personalizadas)
POST /perfil/2fa/enable
GET  /perfil/2fa/qr
POST /perfil/2fa/disable
```
