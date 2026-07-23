# AGENTS.md — Minimal SEO Theme

Guía para agentes de IA (Cursor) que editan este tema WordPress.

**Versión actual:** 2.6.4  
**Ruta del tema:** `wp-content/themes/minimal-seo-theme/`  
**Reglas Cursor:** `.cursor/rules/minimal-seo-theme*.mdc` (workspace raíz)

---

## Qué es este proyecto

Tema WordPress **clásico** (no FSE/block theme) ultraligero, inspirado en Orbital Theme. Prioridades en orden:

1. **WPO** — PageSpeed 100/100 sin plugins de caché
2. **SEO técnico** — H1 único, JSON-LD, crawl budget, silos
3. **Cero bloatware** — sin jQuery, Bootstrap, Google Fonts ni page builders

El usuario espera respuestas en **español**.

---

## Arquitectura

```
minimal-seo-theme/
├── functions.php          # Setup, enqueue, requires (no inflar)
├── style.css              # Cabecera + versión del tema
├── header.php / footer.php
├── index.php              # Portada modo blog + archivos
├── front-page.php         # Portada estática (página fija asignada)
├── single.php / page.php / archive.php
└── inc/
    ├── critical.css       # CSS crítico inline
    ├── customizer.php     # Colores, tipografía, diseño, clusters, AdSense
    ├── seo.php            # Breadcrumbs, schema, redirects, oblinks
    ├── toc.php            # TOC automática (3+ H2)
    ├── load-more.php      # REST mst/v1/load-more
    ├── home-builder.php   # Constructor de inicio (Customizer)
    ├── meta-fields.php    # Subtítulo, entradilla, CTA, hide featured
    ├── content-architecture.php  # Tipos, matriz, validación, brief JSON
    ├── architecture-ecommerce-tree.php  # Árbol decisión e-commerce
    ├── architecture-admin.php    # Meta box editor + Matriz SEO admin
    ├── architecture-beginner-guide.php
    ├── architecture-links.php    # Auto-linkify + bloqueo publicación
    ├── block-patterns.php # Patrones Gutenberg del tema
    └── demo-content.php   # Seed demo + modo blog
└── assets/css/ + assets/js/
```

### Constantes y prefijos

- `MST_VERSION`, `MST_DIR`, `MST_URI`
- Funciones: prefijo `mst_`
- Theme mods: prefijo `mst_` (ej. `mst_home_hero_title`)
- Post meta: prefijo `_mst_` (ej. `_mst_subtitle`)
- Options de seed: `mst_home_seeded`, `mst_demo_seeded`, `mst_demo_post_id`

---

## Jerarquía SEO (no romper)

| Plantilla | H1 | Tarjetas / clusters |
|-----------|----|---------------------|
| Home blog (`index.php`, `show_on_front=posts`) | Nombre del sitio | H2 |
| Home estática (`front-page.php`) | Título de la página | — |
| Single / Page | Título del contenido | — |
| Archivo | Título del archivo | H2 |
| Clusters `[cluster]` | — | `<p class="cluster-card__title">` |

Footer y widgets: **nunca** H1–H6 → usar `<p class="footer-widget__label">`.

---

## Módulos clave

### Constructor de inicio (`inc/home-builder.php`)

- Customizer → **Constructor de inicio**
- Secciones: Hero, Cluster, Posts (`#ultimas-publicaciones`), CTA
- Render: `mst_render_home_top_sections()`, `mst_render_home_bottom_sections()`
- Defaults en `mst_home_defaults()`; seed vía option `mst_home_seeded`

### Contenido demo (`inc/demo-content.php`)

- Hook: `admin_init` (solo admin, **nunca** en frontend)
- Configura modo blog, elimina Hello world / Sample Page, crea categoría + entrada demo
- Bump `mst_demo_seeded` al cambiar el seed (ej. `'1.5.4'`)
- Ancla portada: `#ultimas-publicaciones`; en entradas usar `mst_get_home_posts_anchor( true )`

### SEO (`inc/seo.php`)

- JSON-LD en `<head>` (Article, BreadcrumbList, WebSite)
- No duplicar con microdata HTML
- Redirects 301: adjuntos, feeds, archivos fecha/autor

### Assets

- JS: vanilla ES6, IIFE, siempre `defer`
- CSS: variables `:root`, mobile-first, `inc/critical.css` para above-the-fold
- **Prohibido:** jQuery, CDN de fuentes, frameworks CSS

---

## Flujos comunes

### Añadir opción al Customizer

1. Default en `mst_*_defaults()` del módulo correspondiente
2. `add_setting` + `add_control` con `sanitize_callback` existente
3. Leer con `get_theme_mod()` o helper `mst_get_home_mod()` / equivalente
4. Usar en plantilla o función render

### Añadir campo meta en entradas

1. Registrar en `mst_register_post_meta()` (`inc/meta-fields.php`)
2. UI en meta box existente
3. Mostrar con `mst_get_field()` / `mst_the_entry_*()`
4. Sanitizar en `mst_sanitize_post_meta()`

### Añadir feature SEO/WPO

1. Lógica en `inc/` apropiado, no en `functions.php`
2. Verificar impacto en peso de página y jerarquía H1
3. Si hay schema nuevo → JSON-LD en `wp_head`

### Cambiar versión

1. `MST_VERSION` en `functions.php`
2. `Version:` en `style.css`
3. Changelog en `doc/README.md`
4. Si cambia seed demo → bump `mst_demo_seeded`
5. Regenerar ZIP (ver abajo)

---

## Empaquetado ZIP

WordPress falla si el ZIP usa backslashes Windows. Regenerar con Python:

```python
# Arcnames: minimal-seo-theme/archivo.php (forward slashes)
# Excluir: .git, node_modules, .vscode
# Salida: wp-content/themes/minimal-seo-theme.zip
```

Incluir `README.md` (índice), `doc/README.md` y `doc/AGENTS.md` en el paquete.

---

## Entorno local

- Sitio: `http://seo.local/` (XAMPP)
- Tema clásico → **Personalizar**, no Editor del sitio (FSE)
- Seed demo: entrar a `/wp-admin` una vez tras activar tema
- Linter PHP: stubs WordPress en `vendor/`; falsos positivos de Intelephense son normales

---

## Prohibiciones (resumen)

Ver reglas completas en `.cursor/rules/`.

- Bootstrap, Tailwind, jQuery, Google Fonts, page builders
- Encolar block library en frontend sin necesidad
- Helpers de una línea, abstracciones prematuras, tests triviales no pedidos
- Commits o PRs **solo si el usuario lo pide**
- Editar markdown no solicitado (excepto README/changelog al release)

---

## Checklist antes de entregar

- [ ] Cambio mínimo y alineado con convenciones existentes
- [ ] Sin regresión SEO (H1, schema, breadcrumbs)
- [ ] Sin JS/CSS bloqueante ni dependencias nuevas
- [ ] `php -l` en archivos PHP tocados
- [ ] Versión + `doc/README.md` si es release
- [ ] ZIP regenerado si se empaqueta para distribución
