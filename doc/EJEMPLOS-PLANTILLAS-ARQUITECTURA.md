# Plantillas duplicables — cluster completo de ejemplo

**Tema de ejemplo:** Bienestar y sensaciones (adapta títulos e IDs a tu nicho).

**Orden de creación:** 1 → Pilar · 2 → Categorías · 3 → Artículos específicos.

```
PIL-BIEN (Pilar)
├── CAT-SEN (Categoría: Sensaciones)
│   ├── POS-SEN-01 (Informativo)
│   ├── POS-SEN-02 (Comparativo)
│   ├── POS-SEN-03 (Diagnóstico)
│   └── POS-SEN-04 (Guía de compra)
└── CAT-SEG (Categoría: Seguridad) ← enlace cruzado
```

**Regla de oro:** duplica la fila de la plantilla, cambia IDs/títulos y mantén la **misma lógica** de padre → hijos → enlaces.

---

## Cómo usar cada plantilla

1. En WordPress: **Páginas** (Pilar/Categoría) o **Entradas** (artículos).
2. Copia el bloque **«Meta box — qué pegar»** en **Arquitectura SEO e interlinking**.
3. Escribe el artículo siguiendo **«Estructura del contenido»**.
4. Incluye en el texto las **frases ancla** (para enlaces automáticos).
5. Guarda → revisa avisos rojos → publica.

---

## 1. POST PILAR

| Campo | Valor ejemplo |
|-------|---------------|
| **Título WordPress** | Guía completa de bienestar íntimo y sensaciones |
| **Tipo WP sugerido** | Página (índice del tema) o Entrada larga |
| **Palabras** | 3.000 – 5.500 |

### Meta box — qué pegar

| Campo | Valor |
|-------|-------|
| ID | `PIL-BIEN` |
| Tipo | Post Pilar |
| Parent ID | *(vacío)* |
| links_out | `CAT-SEN, CAT-SEG, POS-SEN-04, SHOP-MAIN` |
| links_in | `CAT-SEN, CAT-SEG` |
| Decisión comercial | Marcada |
| Árbol e-commerce | Sin intención en el pilar → todas las casillas comerciales **desmarcadas** |
| target_url | *(vacío)* |
| Texto CTA | `Sin enlace comercial` |
| anchor_texts | Ver JSON abajo |

```json
[
  {"target": "CAT-SEN", "anchor": "guía de sensaciones al usar productos"},
  {"target": "CAT-SEG", "anchor": "normas de seguridad e ingredientes"},
  {"target": "POS-SEN-04", "anchor": "criterios para elegir con confianza"}
]
```

### Brief JSON (referencia)

```json
{
  "id": "PIL-BIEN",
  "page_title": "Guía completa de bienestar íntimo y sensaciones",
  "parent_id": "",
  "content_type": "Post Pilar",
  "word_count_range": "3000-5500",
  "links_out": ["CAT-SEN", "CAT-SEG", "POS-SEN-04", "SHOP-MAIN"],
  "links_in": ["CAT-SEN", "CAT-SEG"],
  "ecommerce_decision_tree": {
    "treats_pain_or_injury": false,
    "has_commercial_intent": false,
    "requires_comparison": false,
    "product_is_validated": false,
    "product_url_is_stable": false,
    "action_result": "OMIT_CTA",
    "target_url": ""
  },
  "anchor_texts": [
    {"target": "CAT-SEN", "anchor": "guía de sensaciones al usar productos"},
    {"target": "CAT-SEG", "anchor": "normas de seguridad e ingredientes"}
  ]
}
```

### Estructura del contenido (vista)

- **H1:** Guía completa de bienestar íntimo y sensaciones  
- Mapa del tema (lista de categorías, **sin** desarrollar cada rama al detalle)  
- Párrafos con frases ancla hacia `CAT-SEN`, `CAT-SEG`  
- Bloque final: «Empieza por sensaciones» → enlace a categoría  
- **No** agotar temas que van en artículos hijos  

### Qué ve el visitante

Página índice amplia, enlaces a categorías y guías clave; sin CTA comercial principal en el pilar.

---

## 2. POST CATEGORÍA

| Campo | Valor ejemplo |
|-------|---------------|
| **Título WordPress** | Sensaciones al usar productos: guía por tipos |
| **Tipo WP sugerido** | Página índice del cluster + shortcode `[cluster]` |
| **Palabras** | 1.800 – 3.500 |

### Meta box — qué pegar

| Campo | Valor |
|-------|-------|
| ID | `CAT-SEN` |
| Tipo | Post Categoría |
| Parent ID | `PIL-BIEN` |
| links_out | `PIL-BIEN, POS-SEN-01, POS-SEN-02, POS-SEN-03, POS-SEN-04, CAT-SEG, SHOP-SEN` |
| links_in | `PIL-BIEN, POS-SEN-01, POS-SEN-02, POS-SEN-03, POS-SEN-04` |
| Decisión comercial | Marcada |
| Árbol | Intención comercial **sí** · Comparación **sí** · Resto **no** |
| target_url | `https://tutienda.com/categoria/sensaciones` |
| Texto CTA | `Ver productos por tipo de sensación` |
| anchor_texts | Ver JSON |

```json
[
  {"target": "PIL-BIEN", "anchor": "volver al mapa general de bienestar"},
  {"target": "POS-SEN-01", "anchor": "qué es el efecto cálido"},
  {"target": "POS-SEN-02", "anchor": "comparativa entre sensación e irritación"},
  {"target": "CAT-SEG", "anchor": "seguridad cuando hay molestias"}
]
```

### Estructura del contenido (vista)

- **H1:** Sensaciones al usar productos  
- Criterios para entender el cluster (qué es normal, qué no)  
- Enlaces descendentes a cada artículo hijo  
- Puente comercial suave hacia tienda (categoría filtrada)  
- Tarjetas `[cluster category="sensaciones"]` si usas el bloque del tema  

### Qué ve el visitante

Hub intermedio: sube al pilar, baja a artículos, cruza a seguridad, opción de ir a tienda.

---

## 3. INFORMATIVO

| Campo | Valor ejemplo |
|-------|---------------|
| **Título WordPress** | Qué es el efecto cálido (y qué no es) |
| **Tipo WP sugerido** | Entrada |
| **Palabras** | 800 – 1.600 |

### Meta box — qué pegar

| Campo | Valor |
|-------|-------|
| ID | `POS-SEN-01` |
| Tipo | Informativo |
| Parent ID | `CAT-SEN` |
| links_out | `CAT-SEN, POS-SEN-02, POS-SEN-03, ING-01` |
| links_in | `CAT-SEN, POS-SEN-02, POS-SEN-04` |
| Decisión comercial | Marcada |
| Árbol | Sin dolor · Sin intención comercial fuerte → **OMIT_CTA** |
| target_url | *(vacío)* |
| Texto CTA | `Sin enlace comercial` |
| anchor_texts | Ver JSON |

```json
[
  {"target": "CAT-SEN", "anchor": "volver a la guía de sensaciones"},
  {"target": "POS-SEN-02", "anchor": "comparativa entre sensación e irritación"},
  {"target": "POS-SEN-03", "anchor": "cuándo consultar si hay ardor persistente"}
]
```

**En el texto incluye literalmente:** «comparativa entre sensación e irritación» en un párrafo.

### Estructura del contenido (vista)

- **H1:** Qué es el efecto cálido  
- Definición clara en 2–3 párrafos  
- Diferencia educativa vs. irritación (sin vender)  
- Enlace ascendente a categoría madre  
- Enlaces horizontales a comparativo y diagnóstico  

### Qué ve el visitante

Artículo corto educativo, relacionados abajo (`links_out`), sin botón comercial principal.

---

## 4. COMPARATIVO

| Campo | Valor ejemplo |
|-------|---------------|
| **Título WordPress** | Sensación agradable vs. irritación: cómo distinguirlas |
| **Tipo WP sugerido** | Entrada |
| **Palabras** | 1.200 – 2.200 |

### Meta box — qué pegar

| Campo | Valor |
|-------|-------|
| ID | `POS-SEN-02` |
| Tipo | Comparativo |
| Parent ID | `CAT-SEN` |
| links_out | `CAT-SEN, POS-SEN-01, POS-SEN-04, SHOP-SEN-FILTRO` |
| links_in | `CAT-SEN, POS-SEN-01, POS-SEN-04` |
| Decisión comercial | Marcada |
| Árbol | Intención **sí** · Comparación **sí** · Dolor **no** |
| target_url | `https://tutienda.com/categoria/sensaciones?filtro=suave` |
| Texto CTA | `comparar opciones en la tienda` |
| anchor_texts | Ver JSON |

```json
[
  {"target": "POS-SEN-01", "anchor": "qué es el efecto cálido"},
  {"target": "POS-SEN-04", "anchor": "guía para elegir según tu sensibilidad"},
  {"target": "CAT-SEN", "anchor": "mapa completo de sensaciones"}
]
```

**En el texto incluye:** «comparar opciones en la tienda» cerca del cierre.

### Estructura del contenido (vista)

- **H1:** Sensación vs. irritación  
- Tabla o lista: señales de cada caso  
- Cuándo parar y leer diagnóstico  
- CTA comercial → categoría **filtrada** (no producto suelto)  

### Qué ve el visitante

Comparativa clara + enlace comercial a listado filtrado + artículos relacionados.

---

## 5. DIAGNÓSTICO

| Campo | Valor ejemplo |
|-------|---------------|
| **Título WordPress** | Ardor persistente tras usar un producto: ¿normal o alerta? |
| **Tipo WP sugerido** | Entrada |
| **Palabras** | 1.000 – 1.800 |

### Meta box — qué pegar

| Campo | Valor |
|-------|-------|
| ID | `POS-SEN-03` |
| Tipo | Diagnóstico |
| Parent ID | `CAT-SEN` |
| links_out | `CAT-SEN, CAT-SEG, POS-SEN-01, POS-SEN-02, PROF-01` |
| links_in | `POS-SEN-01, POS-SEN-02, CAT-SEN` |
| Decisión comercial | Marcada |
| Árbol | **Dolor/irritación: SÍ** → resto irrelevante → **OMIT_CTA** |
| target_url | *(vacío)* |
| Texto CTA | `Sin enlace comercial` |
| anchor_texts | Ver JSON |

```json
[
  {"target": "CAT-SEG", "anchor": "guía de seguridad e ingredientes a evitar"},
  {"target": "POS-SEN-02", "anchor": "diferencias entre sensación e irritación"},
  {"target": "PROF-01", "anchor": "cuándo pedir cita con un profesional"}
]
```

### Estructura del contenido (vista)

- **H1:** Ardor persistente: ¿normal o alerta?  
- Síntomas y duración  
- Causas frecuentes vs. señales de alerta  
- Enlace a seguridad y orientación profesional  
- **Sin** CTA de compra  

### Qué ve el visitante

Contenido de prevención/salud; el árbol bloquea automáticamente el CTA comercial.

---

## 6. GUÍA DE COMPRA

| Campo | Valor ejemplo |
|-------|---------------|
| **Título WordPress** | Cómo elegir productos según tu sensibilidad |
| **Tipo WP sugerido** | Entrada (money page del cluster) |
| **Palabras** | 1.300 – 2.500 |

### Meta box — qué pegar

| Campo | Valor |
|-------|-------|
| ID | `POS-SEN-04` |
| Tipo | Guía de compra |
| Parent ID | `CAT-SEN` |
| links_out | `CAT-SEN, POS-SEN-01, POS-SEN-02, PROD-VALID-01, SHOP-SEN` |
| links_in | `CAT-SEN, POS-SEN-02, PIL-BIEN` |
| Decisión comercial | Marcada |
| Árbol | Intención **sí** · Producto validado **sí** · URL estable **sí** |
| target_url | `https://tutienda.com/producto/base-sensible-certificada` |
| Texto CTA | `ver la ficha del producto recomendado` |
| anchor_texts | Ver JSON |

```json
[
  {"target": "POS-SEN-01", "anchor": "entender el efecto cálido antes de comprar"},
  {"target": "POS-SEN-02", "anchor": "comparar sensación e irritación"},
  {"target": "PROD-VALID-01", "anchor": "ficha del producto validado para piel sensible"}
]
```

**Alternativa (URL inestable):** desmarca «URL estable» → acción `LINK_APPROVED_CATEGORY` → `target_url` = categoría aprobada, no ficha.

### Estructura del contenido (vista)

- **H1:** Cómo elegir según sensibilidad  
- Criterios de compra (3–5 puntos)  
- Enlaces a educación previa (informativo/comparativo)  
- Recomendación comercial validada (producto o categoría)  

### Qué ve el visitante

Guía de decisión + enlace comercial directo (si ficha estable) + relacionados.

---

## Tabla resumen — copiar y adaptar

| ID | Tipo | Parent | Palabras | CTA comercial |
|----|------|--------|----------|---------------|
| PIL-BIEN | Pilar | — | 3000–5500 | Omitir |
| CAT-SEN | Categoría | PIL-BIEN | 1800–3500 | Categoría filtrada |
| POS-SEN-01 | Informativo | CAT-SEN | 800–1600 | Omitir |
| POS-SEN-02 | Comparativo | CAT-SEN | 1200–2200 | Categoría filtrada |
| POS-SEN-03 | Diagnóstico | CAT-SEN | 1000–1800 | Omitir (dolor) |
| POS-SEN-04 | Guía compra | CAT-SEN | 1300–2500 | Producto o categoría |

---

## Checklist al duplicar una plantilla

- [ ] ID único (no repetido en el sitio)  
- [ ] Parent ID apunta a una página que **ya existe**  
- [ ] links_out con **mínimo 2** IDs (artículos específicos)  
- [ ] Frases ancla escritas **dentro** del contenido  
- [ ] Árbol e-commerce coherente con el tipo (diagnóstico = dolor)  
- [ ] Sin avisos **rojos** al guardar  

---

## Duplicar en WordPress (pasos rápidos)

1. **Entradas → Añadir** (o duplicar entrada existente con un plugin).  
2. Cambia título y `[EDITAR]` del contenido.  
3. Copia la fila de la tabla de arriba al meta box.  
4. Sustituye `tutienda.com` por tu dominio real.  
5. Guarda borrador → corrige rojos → publica.  

*Minimal SEO Theme Premium — plantillas v2.6.4*
