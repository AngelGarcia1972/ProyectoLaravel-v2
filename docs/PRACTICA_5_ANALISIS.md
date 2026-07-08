# Práctica 5: Inyección SQL — Análisis de Código

## Instrucciones

Para cada fragmento de código, marca si es **SEGURO** o **VULNERABLE** a inyección SQL y explica brevemente por qué.

| # | Código | ¿SEGURO o VULNERABLE? | Razón |
|---|--------|------------------------|-------|
| 1 | `DB::raw("SELECT * FROM users WHERE email = '$email'")` | VULNERABLE | Concatena `$email` directamente en la cadena SQL sin escapar ni parametrizar, permitiendo que un atacante inyecte SQL arbitrario modificando el valor de `$email`. |
| 2 | `User::where('email', $email)->first()` | SEGURO | El Query Builder de Eloquent utiliza _parameter binding_ internamente: el valor de `$email` se envía por separado al motor de base de datos, por lo que nunca se interpreta como SQL. |
| 3 | `DB::select("SELECT * FROM orders WHERE id = ?", [$id])` | SEGURO | La sentencia preparada con marcador de posición `?` separa la estructura SQL de los datos; `$id` se vincula como parámetro y no puede alterar la consulta. |
| 4 | `DB::statement('DELETE FROM logs WHERE user=' . $uid)` | VULNERABLE | Se concatenan el string SQL con `$uid` sin ningún tipo de saneamiento o parametrización. Un atacante puede modificar `$uid` para ejecutar comandos SQL maliciosos. |
| 5 | `Producto::whereRaw('nombre = ?', [$nombre])->get()` | SEGURO | Aunque usa `whereRaw`, los valores se pasan como un arreglo de bindings. El motor de BD recibe la consulta y los parámetros por separado, manteniendo la protección. |

## Reflexión

Las consultas parametrizadas (también conocidas como _prepared statements_ o _parameter binding_) previenen la inyección SQL porque separan estrictamente la estructura de la consulta SQL de los datos proporcionados por el usuario. En lugar de construir una cadena SQL concatenando valores directamente —lo que permitiría a un atacante alterar la estructura de la sentencia—, el motor de base de datos recibe primero el esqueleto de la consulta con marcadores de posición (`?` o `:nombre`) y luego recibe los valores por un canal distinto. Esto garantiza que los datos nunca sean interpretados como código SQL, sin importar qué caracteres especiales contengan. Todas las herramientas modernas de acceso a bases de datos en Laravel (Eloquent ORM, Query Builder, y el método `DB::select()` con bindings) utilizan este mecanismo de forma transparente.

---

## Cómo demostrar

### a) Probar la inyección SQL contra el endpoint vulnerable

1. Abre en el navegador: `http://localhost:8000/productos/demo-vulnerable`
2. En el campo "Nombre del producto a buscar", ingresa el siguiente _payload_:

   ```
   ' OR '1'='1
   ```

3. Haz clic en "Buscar (vulnerable)".
4. Verás que **la consulta devuelve todos los productos** en lugar de solo uno. El cuadro "Consulta SQL generada" mostrará:

   ```sql
   SELECT * FROM productos WHERE nombre = '' OR '1'='1'
   ```

   La inyección logró que la condición `WHERE` siempre sea verdadera, devolviendo todas las filas.

### b) Probar el mismo payload contra el endpoint seguro

1. Abre en el navegador: `http://localhost:8000/productos/buscar`
2. En el campo "Nombre", ingresa exactamente el mismo _payload_:
   ```
   ' OR '1'='1
   ```
3. Haz clic en "Buscar".
4. Verás que **NO se devuelven resultados**. El Query Builder escapa el input y lo trata como un valor literal en el `LIKE`, buscando productos cuyo nombre contenga la cadena `' OR '1'='1` — que no existe.

### c) Revisar el log de consultas SQL

1. Abre el archivo de log diario de Laravel:
   ```bash
   cat storage/logs/laravel-$(date +%Y-%m-%d).log
   ```
2. Busca las líneas que contengan `local.DEBUG: Query`.
3. Para la búsqueda segura verás algo como:
   ```
   local.DEBUG: Query {"sql":"select * from `productos` where `nombre` like ? ...","bindings":["%' OR '1'='1%"],"time":...}
   ```
   Nota que el payload aparece dentro de los **bindings** (parámetros vinculados), no en la cadena SQL. Esto demuestra que el valor nunca se interpretó como código SQL.

4. Para la búsqueda vulnerable verás:
   ```
   local.DEBUG: Query {"sql":"SELECT * FROM productos WHERE nombre = '' OR '1'='1'","bindings":[],"time":...}
   ```
   La cadena SQL ya contiene el payload inyectado y el arreglo de bindings está vacío, confirmando que no se usó parametrización.
