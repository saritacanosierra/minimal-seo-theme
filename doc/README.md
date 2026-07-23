# Minimal SEO Theme Premium

El tema de WordPress definitivo para **nichos y SEO de marca**. Ultraligero, modular y mobile-first: clusters en silo, Menú Órbita con ofuscación de enlaces, TOC automática, conversión WebP y plantilla demo guiada (TEMA 1 + TEMA 2).

**Versión:** 2.6.8  
**Requisitos:** WordPress 6.0+, PHP 7.4+  
**Repositorio:** [github.com/saritacanosierra/minimal-seo-theme](https://github.com/saritacanosierra/minimal-seo-theme)

---

## Características principales

- **Cero bloatware:** sin Bootstrap, Tailwind ni jQuery
- **CSS nativo:** Grid + Flexbox, variables CSS, mobile-first
- **JavaScript vanilla ES6** con carga `defer`
- **CSS crítico inline** en el `<head>` para above-the-fold
- **Menú Órbita** radial en móvil con animación GPU y ofuscación de enlaces duplicados
- **Shortcode `[cluster]`** para arquitectura en silo (TEMA 1, TEMA 2…)
- **Plantilla demo** con textos `[EDITAR]` y guía en el admin
- **Conversión automática a WebP** al subir imágenes
- **Monetización** AdSense / afiliados al final de cada entrada
- **Jerarquía SEO estricta** de H1–H6
- **Customizer** para colores, tipografía, diseño, clusters, AdSense y constructor de inicio
- **Constructor de inicio** (Hero, cluster, posts, CTA) sin page builder
- **Campos meta** en entradas/páginas: subtítulo, entradilla, CTA, ocultar destacada
- **Arquitectura SEO e interlinking** (modelo Órbita restrictivo): tipos, rangos de palabras, matriz de enlaces, anclas y brief JSON
- **Patrones de bloques** nativos (Hero, CTA, 2 columnas, Cluster)
- **Contenido demo** automático al activar (modo blog listo para usar)
- **Schema.org** (WebSite + Article + BreadcrumbList)
- **Accesibilidad WCAG:** skip link, ARIA, `:focus-visible`

---

## Instalación

### Opción A — ZIP

1. Descarga o usa `minimal-seo-theme.zip`
2. En WordPress: **Apariencia → Temas → Añadir nuevo → Subir tema**
3. Activa **Minimal SEO Theme Premium**
4. Entra al **admin** una vez: se crea la plantilla demo (TEMA 1 + TEMA 2)
5. Consulta **Apariencia → Guía: qué editar** para personalizar paso a paso

### Opción B — FTP / carpeta local

Copia la carpeta `minimal-seo-theme` en:

```
wp-content/themes/minimal-seo-theme/
```

### Opción C — Generar ZIP (desarrolladores)

Desde la carpeta del tema (requiere Python 3):

```bash
python scripts/package-theme.py
```

Crea `wp-content/themes/minimal-seo-theme.zip` con la estructura correcta para WordPress (excluye `.git` y `scripts/`).

---

## Configuración inicial

### 1. Logo

Ve a **Apariencia → Personalizar → Identidad del sitio → Logo**.

- Usa **SVG o WebP** para mínimo peso (recomendado: ancho máx. 240 px, alto máx. 80 px)
- Si no subes logo, se muestra el nombre del sitio como texto
- En la home con últimas entradas, el logo va dentro del **H1** único (SEO correcto)
- El logo se carga con `fetchpriority="high"` solo en esa home (mejor LCP)

### 2. Menús

Ve a **Apariencia → Menús** y crea:

| Ubicación | Uso |
|-----------|-----|
| **Menú principal** | Navegación desktop y hamburguesa móvil |
| **Menú Órbita (móvil)** | Botón flotante radial (opcional; usa el principal como fallback) |

### 3. Customizer

**Apariencia → Personalizar → Minimal SEO Theme Premium**

#### Colores
- Texto, texto secundario, fondo, acento y acento hover
- Vista previa en vivo sin recargar

#### Tipografía
- Familia tipográfica (fuentes del sistema, sin Google Fonts)
- Tamaño base: 14–22 px
- Interlineado: 1.4 – 2.0

Opciones de fuente:
- System UI (recomendada)
- Segoe UI
- Roboto (si está instalada en el sistema)
- Georgia / Serif / Monoespaciada

#### Diseño
- **Ancho máximo del sitio:** 960 – 1400 px (header, main, footer, grids)
- **Ancho del contenido:** 600 – 800 px (entradas y páginas)

#### Clusters
Valores por defecto del shortcode `[cluster]`:
- Columnas (1–6)
- Entradas por cluster (1–24)
- Texto CTA
- Mostrar extracto

#### Monetización
- Activar zona de publicidad en entradas
- Pegar código AdSense (`<script>` o `<ins>`)
- Etiqueta accesible opcional

#### Constructor de inicio
**Personalizar → Minimal SEO Theme Premium → Constructor de inicio**

Secciones opcionales para la portada en modo blog (`index.php`):

| Sección | Descripción |
|---------|-------------|
| **Hero** | Título H2, texto, botón e imagen de fondo opcional |
| **Cluster** | Cuadrícula de enlaces filtrada por categoría (slug) |
| **Posts** | Título H2 con ancla `#ultimas-publicaciones` + grid de entradas |
| **CTA** | Banner final con botón de acción |

Los textos por defecto se aplican automáticamente la primera vez. El botón Hero y el CTA apuntan a `#ultimas-publicaciones`.

### 4. Campos extra (entradas y páginas)

En el editor, panel lateral **Minimal SEO — Campos extra**:

| Campo | Uso |
|-------|-----|
| **Subtítulo** | Línea bajo el H1 |
| **Entradilla** | Párrafo introductorio antes del contenido |
| **Texto / URL del CTA** | Botón opcional tras el contenido |
| **Ocultar imagen destacada** | No mostrar thumbnail en single/page |

### 5. Patrones de bloques

En el editor de páginas: **Patrones → Minimal SEO Theme Premium**

- Hero — Portada
- Banner CTA
- Dos columnas
- Cluster de enlaces

Útiles para landings estáticas (`front-page.php`) sin plugins.

### 6. Imágenes destacadas

Activa imágenes destacadas en tus entradas y páginas. El tema genera:
- `mst-card` — 640×360 (tarjetas y clusters)
- `mst-hero` — 1200×630 (entradas, Open Graph friendly)

---

## Contenido demo (v1.5+)

Al entrar al admin por primera vez con el tema activo, se ejecuta un seed único:

| Acción | Detalle |
|--------|---------|
| Modo blog | Ajustes → Lectura → *Tu última entrada* |
| Limpieza | Elimina *Hello world!* y *Sample Page* |
| Categoría | `seo-tecnico` (SEO técnico) |
| Entrada demo | `/guia-seo-tecnico-wordpress/` con 3 H2 (TOC), meta fields y CTA |
| Cluster | Enlaza la categoría demo si no había slug configurado |
| Tagline | Actualiza la descripción si sigue siendo la de WordPress por defecto |

Para forzar de nuevo el seed en desarrollo, borra la option `mst_demo_seeded` en la base de datos.

---

## Shortcode `[cluster]`

Crea cuadrículas visuales de enlaces internos para estructuras en silo.

### Uso básico

```
[cluster category="seo" posts_per_page="6" columns="3"]
```

### Atributos disponibles

| Atributo | Descripción | Default |
|----------|-------------|---------|
| `category` / `cat` | Slug o ID de categoría | — |
| `tag` | Slug o ID de etiqueta | — |
| `ids` | IDs de posts separados por coma | — |
| `posts_per_page` | Número de elementos | Customizer |
| `columns` | Columnas (1–6) | Customizer |
| `orderby` | `date`, `title`, `modified`, `menu_order`, `rand` | `date` |
| `order` | `ASC` o `DESC` | `DESC` |
| `featured` | IDs destacados (ocupan fila completa) | — |
| `show_excerpt` | `yes` o `no` | Customizer |
| `cta_text` | Texto del botón | Customizer |

### Ejemplos

```
[cluster category="marketing" columns="4" orderby="title" order="ASC"]

[cluster ids="12,45,78" featured="12" cta_text="Ver guía"]

[cluster tag="wordpress" posts_per_page="9" columns="3" show_excerpt="no"]
```

---

## SEO técnico avanzado

### Enlaces ofuscados

Para páginas legales, contacto u otros enlaces secundarios:

```php
<?php echo mst_obfuscated_link( home_url( '/privacidad/' ), 'Política de privacidad' ); ?>
```

Shortcode:

```
[oblink url="https://tusitio.com/contacto/"]Contacto[/oblink]
```

Genera `<span class="obfuscated-link" data-link="BASE64">`. El JS en `navigation.js` decodifica y navega al clic.

### Breadcrumbs

Automáticos en entradas y páginas con JSON-LD `BreadcrumbList` en el `<head>`.

Ruta típica de entrada: `Inicio > Categoría > Título`

### Crawl budget

| Acción | Comportamiento |
|--------|----------------|
| URLs de adjuntos | 301 al post padre |
| Archivos por fecha | 301 a home |
| Archivos de autor | 301 a home (si solo hay 1 admin) |
| Feeds RSS | Eliminados del head + redirect 301 |

### Schema Article

JSON-LD completo en entradas: `@id`, `mainEntityOfPage`, `description`, `wordCount`, `articleSection`, `author` (con URL), `publisher` (con logo) e `image` como `ImageObject`.

### Tabla de contenidos (TOC)

Generada automáticamente en entradas con **3 o más H2**:
- IDs con slugs limpios en cada H2
- `<nav class="mst-toc">` insertada antes del primer H2
- Colapsar/desplegar con botón nativo (sin librerías)

### Cargar más (AJAX)

- Shortcode `[cluster]` con botón **Cargar más** (fetch API + REST `mst/v1/load-more`)
- Archivos de categoría, etiqueta y autor con la misma paginación
- Atributo `load_more="no"` para desactivar en clusters concretos

### Anti-CLS en anuncios

Customizer → Monetización → **Altura mínima del anuncio** (90 / 250 / 280 / 600 px). Reserva espacio y muestra etiqueta "Publicidad" antes de que cargue AdSense.

---

## Menú Órbita

En pantallas menores a **768px** aparece un botón circular flotante en la esquina inferior derecha. Al pulsarlo, despliega hasta **6 enlaces** en arco semicircular con transiciones CSS (`transform` + `opacity`).

Configura un menú dedicado en **Apariencia → Menús → Menú Órbita (móvil)** o deja que use el menú principal automáticamente.

Los enlaces que **repiten el menú de cabecera** (Contacto, legal, etc.) se ofuscan con `mst_obfuscated_link()` para no duplicar `href` visibles. Clases CSS del ítem: `mst-obfuscate` (forzar) o `mst-no-obfuscate` (evitar).

---

## Jerarquía SEO de encabezados

| Plantilla | H1 | Tarjetas / clusters |
|-----------|----|---------------------|
| Home (últimas entradas) | Nombre del sitio | H2 |
| Home estática | Título de la página | — |
| Entrada / Página | Título del contenido | — |
| Archivo (categoría, etiqueta) | Título del archivo | H2 |
| Footer / widgets | Sin H1–H6 | `<p class="footer-widget__label">` |

---

## Estructura de archivos

```
minimal-seo-theme/
├── style.css                 # Cabecera del tema
├── functions.php             # Setup, optimizaciones, shortcode
├── header.php
├── footer.php
├── index.php                 # Portada blog + listados
├── front-page.php            # Portada estática (página fija)
├── single.php
├── page.php
├── archive.php
├── README.md                 # Índice (enlace a doc/)
├── doc/                      # Documentación de lectura
│   ├── README.md             # Referencia técnica completa
│   ├── AGENTS.md
│   ├── GUIA-MAPA-DECISIONES-CLIENTE.md
│   ├── GUIA-FACIL-ENLACES-ARTICULOS.md
│   └── EJEMPLOS-PLANTILLAS-ARQUITECTURA.md
├── inc/
│   ├── critical.css          # CSS crítico (inline en head)
│   ├── customizer.php        # Panel del Customizer
│   ├── seo.php               # Breadcrumbs, schema, crawl budget
│   ├── toc.php               # Tabla de contenidos automática
│   ├── load-more.php         # REST AJAX cargar más
│   ├── home-builder.php      # Constructor de inicio
│   ├── meta-fields.php       # Campos extra entradas/páginas
│   ├── block-patterns.php    # Patrones Gutenberg
│   └── demo-content.php      # Seed demo + modo blog
└── assets/
    ├── css/
    │   ├── theme.css         # Estilos principales
    │   └── editor.css        # Estilos del editor
    └── js/
        ├── navigation.js     # Menú hamburguesa + Órbita + oblinks
        ├── load-more.js      # Paginación AJAX
        └── customizer-preview.js
```

---

## Optimizaciones de rendimiento

El tema aplica automáticamente:

- Eliminación de emojis, RSD, wlwmanifest, generator, oEmbed
- Dequeue de estilos Gutenberg en frontend
- jQuery desregistrado en frontend
- Lazy loading nativo en imágenes
- Preload del CSS principal
- Scripts con `defer`

### Recomendaciones para PageSpeed 100/100

1. **No uses plugins de caché duplicados** — el tema ya está optimizado
2. Sirve imágenes en **WebP/AVIF**
3. Activa **Brotli/Gzip** en el servidor
4. Evita page builders y sliders pesados
5. Usa un hosting con HTTP/2 o HTTP/3
6. Mantén plugins al mínimo (Yoast/Rank Math para meta avanzadas está bien)

---

## Compatibilidad

- Compatible con plugins SEO (Yoast, Rank Math) para meta descriptions y sitemaps
- Sin dependencias de WooCommerce (estilos WC Blocks desencolados si existen)
- Editor clásico y Gutenberg soportados (estilos de bloques no cargados en frontend)

---

## Licencia

GNU General Public License v2 or later  
https://www.gnu.org/licenses/gpl-2.0.html

---

## Changelog

### 2.6.8 — Biblioteca de medios sin saturación demo
- La plantilla ya no crea un adjunto por cada entrada demo (tarjetas con gradientes CSS)
- Migración automática que elimina duplicados «Imagen de ejemplo» / `mst-demo-*`

### 2.6.7 — Cluster cards con distribución uniforme
- Cuadrícula interna: meta arriba, CTA abajo, alturas iguales en la fila
- Título y extracto con line-clamp y espacio reservado para alineación armónica

### 2.6.6 — Menú demo con enlace Inicio
- Menú principal sembrado con **Inicio** (portada) + TEMA 1 + TEMA 2
- Migración automática que añade «Inicio» si falta en menús existentes

### 2.6.5 — Portada más clara y tarjetas demo sin superposición
- Gradientes abstractos en tarjetas demo (sin texto de imagen superpuesto al título)
- Guía «¿Qué es esta página?» en portada demo (portada ≠ guía completa / pilar)
- Títulos de sección en cuadrícula cluster y lista de artículos; sin duplicar TEMA 1 en la lista inferior

### 2.6.4 — Cards del cluster sin enlaces anidados
- Corregido HTML inválido (`<a>` dentro de `<a>`) en `[cluster]` que dejaba título/extracto invisibles en páginas hub
- Enlace principal como stretched link absoluto; categoría en meta como `<span>`
- CSS: texto blanco en overlay sin depender del enlace padre

### 2.6.3 — Mapa de decisiones del cliente
- **Apariencia → Mapa de decisiones**: vocabulario PDF ↔ WordPress, 8 puertas, árbol e-commerce y orden de publicación
- Guía `doc/GUIA-MAPA-DECISIONES-CLIENTE.md` y aclaración TEMA 1 = territorio (Post categoría), no pilar
- Enlaces desde meta box, guía fácil y guía de plantilla hacia el mapa del cliente

### 2.6.2 — Árbol e-commerce + plantillas duplicables
- Diagrama de flujo comercial (`ecommerce_decision_tree` en el brief JSON)
- **Apariencia → Plantillas SEO** y `doc/EJEMPLOS-PLANTILLAS-ARQUITECTURA.md` (pilar, categoría, informativo, comparativo, diagnóstico, guía de compra)

### 2.6.1 — Enlaces automáticos y publicación bloqueada
- Inserción de enlaces al guardar desde `anchor_texts`, `links_out`, padre y URL comercial
- Bloque «Sigue leyendo en este tema» para enlaces que no aparecen en el texto
- Publicación bloqueada si hay errores críticos de arquitectura (borrador automático)
- Guía fácil para principiantes en **Apariencia → Guía fácil: enlaces**

### 2.6.0 — Arquitectura SEO e interlinking operacional
- Tipos de contenido con rangos de extensión: Pilar, Categoría, Informativo, Comparativo, Diagnóstico, Guía de compra
- Meta box **Arquitectura SEO e interlinking** en entradas y páginas (Parent ID, links_in/out, e-commerce, anchor_texts)
- Validación al guardar: palabras, jerarquía, anclas prohibidas y decisión comercial
- Export de matriz JSON en **Apariencia → Matriz SEO**
- Entradas relacionadas priorizan `links_out` de la matriz antes del fallback por categoría

### 2.5.5 — Premium + Menú Órbita pro
- Rebrand **Minimal SEO Theme Premium**
- Ofuscación inteligente en Menú Órbita (enlaces duplicados del menú principal)
- Animación radial 100 % GPU (`translate3d`, `opacity`, sin reflow)
- Plantilla demo **TEMA 1 + TEMA 2**, títulos cortos, imágenes WebP
- Conversión automática JPG/PNG → WebP
- Fix caja de código en Monetización (Customizer)
- Guía admin: **Apariencia → Guía: qué editar**

### 2.2.0 — Silo Orbital completo
- Página pilar estática `/seo-tecnico/` con cluster integrado
- Entrada money `/auditoria-seo-tecnica-wordpress/` (checklist conversión)
- Hero de portada enlaza a la página pilar
- Descripción de categoría con enlace al hub

### 2.1.0 — Silo demo 10/10
- 4 entradas enlazadas en categoría `seo-tecnico` (pilar + CWV + Schema + Crawl budget)
- Imágenes SVG por entrada, 2 destacadas en cluster
- Enlazado interno entre guías del silo
- Cluster de portada solo con entradas (sin páginas)
- Entradas relacionadas activas con 3 posts

### 2.0.0 — Paridad Orbital
- Clusters enriquecidos: categoría, fecha, autor y descripción (meta > Yoast > extracto)
- Destacados automáticos + checkbox por entrada en el editor
- Bloque Gutenberg `mst/cluster` (render server-side, 0 JS en frontend)
- Sidebar configurable (unibody / izquierda / derecha)
- Entradas relacionadas por categoría
- Imagen destacada demo + meta cluster en seed v2.0.0

### 1.5.4
- Elimina *Sample Page* en el seed demo
- CTAs de portada apuntan a `#ultimas-publicaciones`
- CTA de la entrada demo usa URL absoluta con ancla

### 1.5.3
- Seed configura el sitio en **modo blog** (últimas entradas en portada)
- Elimina automáticamente *Hello world!*
- Actualiza tagline por defecto de WordPress

### 1.5.2
- Contenido demo: categoría `seo-tecnico` + entrada con TOC y meta fields
- Enlaza cluster de portada a la categoría demo

### 1.5.1
- Textos por defecto del constructor de inicio (Hero, cluster, CTA)
- Seed automático de theme mods (`mst_home_seeded`)
- Ancla `#ultimas-publicaciones` en título de sección posts

### 1.5.0
- Constructor de inicio en Customizer (Hero, cluster, posts, CTA)
- Campos meta: subtítulo, entradilla, CTA, ocultar destacada
- 4 patrones de bloques nativos
- `front-page.php` para portada estática con constructor

### 1.4.0
- Tabla de contenidos nativa (TOC) automática con 3+ H2
- Paginación AJAX "Cargar más" en clusters y archivos
- Placeholders anti-CLS para zonas de AdSense

### 1.3.0
- Enlaces ofuscados (`mst_obfuscated_link()` + shortcode `[oblink]`)
- Breadcrumbs semánticos con JSON-LD BreadcrumbList
- LCP optimizado: featured eager, contenido lazy
- Crawl budget: redirect adjuntos, archivos fecha/autor, RSS deshabilitado
- Schema Article completo (autor, publisher, wordCount, imageObject)

### 1.2.1
- Logo personalizado nativo (Identidad del sitio)
- `fetchpriority="high"` en logo de home para LCP

### 1.2.0
- Customizer: tipografía (fuente, tamaño, interlineado)
- Customizer: ancho del sitio y del contenido
- README completo

### 1.1.0
- Panel Customizer (colores, clusters, AdSense)
- Preview en vivo de colores

### 1.0.0
- Lanzamiento inicial
