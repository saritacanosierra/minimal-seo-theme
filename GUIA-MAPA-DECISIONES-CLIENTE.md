# Guía del cliente — Mapa de decisiones SEO ↔ Plantilla WordPress

**Para la primera empresa que usa esta plantilla.**  
Basado en el documento *Mapa operativo de decisiones SEO · Arquitectura editorial y comercial*.

---

## Regla de oro (del mapa)

> **Arquitectura antes que redacción.**  
> Primero defines la función de cada URL (pilar, categoría, tipo de post, enlaces, marketplace). Después escribes.

> **Educación antes que venta.**  
> El enlace al ecommerce solo cuando el usuario ya entiende la necesidad, los criterios y las precauciones.

En WordPress esto se hace en la caja **«Arquitectura SEO e interlinking»** **antes** de redactar.

---

## Vocabulario: PDF ↔ lo que ves en WordPress

| En el mapa de decisiones | En la plantilla demo | Qué es en la práctica |
|--------------------------|----------------------|------------------------|
| **Territorio** (Sensaciones, Sabores, Piel sensible…) | **TEMA 1**, **TEMA 2** (ejemplo genérico) | Un bloque temático de tu negocio |
| **Post categoría** | Página `/tema-1/` + categoría WP | Índice del territorio; agrupa artículos |
| **Post pilar** | *(no viene en demo; créalo si hace falta)* | Mapa de **todos** los territorios |
| **Post específico** | Art. 1, Art. 2… | Informativo, comparativo, diagnóstico o guía |
| **Guía de compra / conversión** | **Oferta** | Página para contratar o comprar |
| **Marketplace / ecommerce tercero** | URL en `target_url` + árbol e-commerce | Tienda externa; enlace solo si está validado |

### ¿El «TEMA» de la demo es el pilar?

**No.** En la demo, **TEMA 1 = Post categoría** (un territorio concreto).

El **Post pilar** sería una página encima, por ejemplo:

```
PIL-01  →  «Lubricantes íntimos a base de agua: guía completa»
   ├── CAT-SEN  →  Territorio Sensaciones  (hoy: TEMA 1)
   ├── CAT-SAB  →  Territorio Sabores
   └── CAT-PIEL →  Territorio Piel sensible
```

Si tu web empieza con **un solo territorio**, puedes usar solo **Post categoría** sin pilar hasta crecer.

---

## Las 8 puertas de control (simplificado)

Antes de **publicar** cada URL, comprueba:

| Puerta | Pregunta | Dónde en WordPress |
|--------|----------|-------------------|
| 1 | ¿El tema encaja con la marca? | Decisión del equipo (no solo del redactor) |
| 2 | ¿Tenemos medición (Analytics)? | Fuera del tema; configurar GA4 |
| 3 | ¿Hay oportunidad SEO viable? | Investigación previa (Semrush, SERP…) |
| 4 | ¿URL, tipo e intención definidos? | Meta box: **ID**, **Tipo**, **Parent ID** |
| 5 | ¿Interlinking y marketplace definidos? | **links_out/in**, **anchor_texts**, **árbol e-commerce** |
| 6 | ¿Brief aprobado? | Vista previa JSON + validación sin errores rojos |
| 7 | ¿Contenido y schema revisados? | Redacción + tema (breadcrumbs, schema) |
| 8 | ¿Publicación conectada? | Enlaces desde padre y relacionados; no publicar «huérfana» |

La plantilla **bloquea la publicación** si hay errores rojos en la puerta 5–6 (arquitectura incompleta).

---

## Tipos de página (mapa §10)

| Tipo | Cuándo crear | Palabras | Ejemplo (lubricantes) |
|------|--------------|----------|------------------------|
| **Post pilar** | Tema amplio; varios territorios | 3.000–5.500 | Guía completa lubricantes base agua |
| **Post categoría** | Familia de necesidades (≥5 posts posibles) | 1.800–3.500 | Sensaciones: efectos cálidos y frescos |
| **Informativo** | Pregunta educativa concreta | 800–1.600 | Qué es el efecto cálido |
| **Comparativo** | Opciones y criterios | 1.200–2.200 | Sensación agradable vs. irritación |
| **Diagnóstico** | Dolor, irritación, alerta | 1.000–1.800 | Ardor persistente: ¿normal o alerta? |
| **Guía de compra** | Criterios y destino validado | 1.300–2.500 | Cómo elegir según sensibilidad |
| **Oferta** *(demo)* | Conversión directa (servicio/venta) | Como guía de compra | Auditoría / landing comercial |

---

## Árbol e-commerce (mapa §12) ↔ casillas del meta box

| Pregunta del mapa | Casilla | Acción resultante |
|-------------------|---------|-------------------|
| ¿Dolor, irritación o lesión? | **Trata dolor…** | Omitir CTA comercial |
| ¿Intención comercial? | **Hay intención comercial** | Si no → omitir CTA |
| ¿Debe comparar opciones? | **Requiere comparación** | Categoría filtrada en marketplace |
| ¿Producto validado y URL estable? | **Producto validado** + **URL estable** | Ficha de producto directa |
| ¿Ficha incompleta o URL inestable? | Producto validado **sin** URL estable | Categoría aprobada o sin enlace |

**Secuencia editorial:** necesidad → respuesta → criterios → precauciones → comparación → ecommerce (no en el primer párrafo).

---

## Matriz mínima (ejemplo del mapa §11.2)

| ID | Página | Padre | Ecommerce |
|----|--------|-------|-----------|
| POS-SEN-01 | Qué es efecto cálido | CAT-SEN | Categoría sensaciones |
| POS-SEN-02 | Frescura vs. irritación | CAT-SEN | Ninguno |
| CAT-SAB | Lubricantes con sabores | PIL-01 | Categoría sabores |

Copia plantillas completas en **Apariencia → Plantillas SEO**.

---

## Artículo vs Oferta (para tu equipo)

| | **Artículo** (post específico) | **Oferta** |
|---|-------------------------------|------------|
| **Objetivo** | Educar, posicionar, enlazar | Convertir (contacto/compra) |
| **Tipo en meta box** | Informativo, comparativo, diagnóstico… | **Guía de compra** |
| **Marketplace** | Según árbol (a menudo omitir en diagnóstico) | Producto o categoría validada |
| **En la demo** | TEMA 1 — Art. 1, 2… | TEMA 1 — Oferta |

---

## Orden de trabajo recomendado (mapa §13.2)

1. **Pilar** (si aplica) + **2–3 categorías** (territorios)
2. **2 posts por categoría** + contenidos de **seguridad/compatibilidad**
3. **Comparativas y guías de compra**
4. Keywords amplias (pilar competitivo) cuando haya soporte

---

## Checklist de aprobación de una URL (mapa §18.1)

- [ ] Tema e intención aprobados  
- [ ] Tipo y Parent ID en meta box  
- [ ] links_out e links_in + anchor_texts  
- [ ] Destino comercial aprobado **o** omitido explícitamente (árbol e-commerce)  
- [ ] Sin errores rojos al guardar  
- [ ] Enlaces desde padre y páginas relacionadas (no huérfana)  
- [ ] Contenido revisado (marca, seguridad si aplica)  

---

## Dónde hacer cada cosa en WordPress

| Tarea | Menú |
|-------|------|
| Editar contenido | Entradas / Páginas |
| Arquitectura de cada URL | Meta box al editar |
| Plantillas duplicables | Apariencia → **Plantillas SEO** |
| Guía paso a paso | Apariencia → **Guía fácil: enlaces** |
| Ver todo el mapa registrado | Apariencia → **Matriz SEO** |
| Portada y clusters visuales | Personalizar → Constructor de inicio |

---

*Minimal SEO Theme Premium — alineado con Mapa de decisiones SEO v1*
