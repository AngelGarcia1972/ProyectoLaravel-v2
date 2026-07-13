# Práctica 9 – Docker Multi-Stage Builds y Análisis de Vulnerabilidades

## ¿Qué son las construcciones multi-stage y por qué reducen la superficie de ataque?

Una construcción multi-stage divide el proceso de build de una imagen Docker en varias etapas, donde cada etapa tiene un propósito específico (instalar dependencias, compilar assets, etc.). La clave está en que solo se copian los artefactos resultantes a la imagen final, dejando fuera todas las herramientas de compilación, dependencias de desarrollo y archivos temporales que se usaron durante el build. Esto reduce significativamente el tamaño de la imagen final y, con ello, la superficie de ataque: menos binarios significa menos posibles vulnerabilidades CVE, menos binarios de alto riesgo como shell de root, y menos herramientas que un atacante podría explotar si logra acceder al contenedor.

## Instrucciones

### 1. Construir la imagen simple (anti-patrón)

```bash
docker build -f Dockerfile.simple -t laravel:simple .
```

### 2. Construir la imagen multi-stage (optimizada)

```bash
docker build -f Dockerfile -t laravel:multistage .
```

### 3. Comparar tamaños de imagen

```bash
docker images | grep laravel
```

### 4. Escanear con Trivy (opción A: usar Docker para ejecutar Trivy)

```bash
docker run --rm -v /var/run/docker.sock:/var/run/docker.sock aquasec/trivy image laravel:multistage
```

### 5. Escanear con Trivy (opción B: Trivy instalado localmente)

```bash
trivy image --severity CRITICAL,HIGH laravel:multistage
```

### 6. Generar reporte en JSON

```bash
trivy image --format json --output trivy-report.json laravel:multistage
```

### 7. Escanear el sistema de archivos del proyecto (dependencias en código fuente)

```bash
trivy fs --severity HIGH,CRITICAL .
```

### 8. Verificar ejecución como usuario no-root

```bash
docker compose up -d app
docker compose exec app whoami
docker compose exec app id
```

## Comparación de tamaños de imagen

| Imagen | Tamaño | Observación |
|---|---|---|
| `laravel:simple` | | |
| `laravel:multistage` | | |

## Vulnerabilidades encontradas (CVEs)

| CVE | Severidad | Paquete afectado | Solución / Mitigación |
|---|---|---|---|
| | | | |
| | | | |
| | | | |

## Reflexión sobre los 5 principios de seguridad

Las construcciones multi-stage aplican directamente los cinco principios de seguridad de la práctica: imágenes base mínimas (Alpine en lugar de Debian completo), usuario no-root (el contenedor multi-stage ejecuta como `laravel:1000` en lugar de `root`), sin secretos expuestos en la imagen (el `.dockerignore` excluye `.env`), capas mínimas (solo se copian los artefactos necesarios, no todo el historial de build), y escaneo de CVEs con Trivy para validar que la superficie de ataque se redujo de forma medible. La comparación de tamaños y resultados de Trivy entre ambas imágenes demuestra que estas prácticas no son solo teóricas: se traducen en una reducción concreta de vulnerabilidades y en una imagen más difícil de explotar en producción.
