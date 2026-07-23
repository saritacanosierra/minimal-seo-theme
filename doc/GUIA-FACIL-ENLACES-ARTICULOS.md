# Guía fácil: cómo conectar tus artículos entre sí

**Para personas que no saben de código ni de marketing.**  
Solo necesitas WordPress y seguir estos pasos con calma.

---

## ¿Para qué sirve esto?

Imagina tu web como una **biblioteca**:

- Hay un **libro grande** que explica el tema general → eso es el **Post Pilar**.
- Hay **estanterías** que agrupan libros parecidos → son los **Post Categoría**.
- Hay **libros concretos** que responden una pregunta → son los artículos normales (Informativo, Comparativo, etc.).

Esta herramienta te ayuda a **decidir antes de escribir** qué artículo enlaza con cuál, para que Google y tus visitantes no se pierdan.

**No tienes que programar nada.** Solo rellenar cajas en WordPress.

---

## ¿Dónde está en WordPress?

1. Entra a **Entradas** o **Páginas**.
2. Abre un artículo para editarlo.
3. Baja hasta la caja **«Arquitectura SEO e interlinking»**.
4. Rellena los campos y pulsa **Actualizar**.

También puedes leer esta guía dentro de WordPress:  
**Apariencia → Guía fácil: enlaces**

---

## Las 3 piezas de tu web (en palabras simples)

| Nombre en la caja | Qué es en la vida real | Ejemplo |
|-------------------|------------------------|---------|
| **Post Pilar** | La página madre de todo un tema | «Todo sobre cuidado de la piel» |
| **Post Categoría** | La página de un grupo dentro del tema | «Sensaciones al usar cremas» |
| **Artículo normal** | Una pregunta concreta | «Qué es el efecto cálido» |

---

## Orden recomendado (hazlo así)

### Paso 1 — Crea primero las páginas «grandes»

1. Crea el **Pilar** (página o entrada larga).
2. Crea la **Categoría** (otra página o entrada).
3. Después crea los **artículos pequeños**.

Así ya tendrás a quién poner como «padre» cuando rellenes los artículos pequeños.

### Paso 2 — Dale a cada uno un «código» (ID)

Es como la **etiqueta del estante**. Tú la inventas. Usa letras y números, sin espacios.

Ejemplos:

- Pilar → `PIL-PIEL`
- Categoría → `CAT-SEN`
- Artículo → `POS-SEN-01`, `POS-SEN-02`

**Regla:** no repitas el mismo código en dos páginas.

### Paso 3 — Elige el tipo de contenido

En la caja desplegable **«Tipo de contenido»**, elige la opción que encaje:

| Si tu texto… | Elige |
|--------------|-------|
| Explica un tema muy grande y enlaza a muchas secciones | Post Pilar |
| Agrupa varios artículos de la misma familia | Post Categoría |
| Responde una pregunta («qué es…», «cómo funciona…») | Informativo |
| Compara opciones («A vs B», «mejor X para Y») | Comparativo |
| Habla de molestias, síntomas o «¿es normal?» | Diagnóstico |
| Ayuda a elegir qué comprar | Guía de compra |

### Paso 4 — Cuánto debe medir el texto (palabras)

WordPress te avisa si te pasas o te quedas corto. No hace falta contar a mano.

| Tipo | Longitud aproximada |
|------|---------------------|
| Post Pilar | Entre 3.000 y 5.500 palabras |
| Post Categoría | Entre 1.800 y 3.500 |
| Informativo | Entre 800 y 1.600 |
| Comparativo | Entre 1.200 y 2.200 |
| Diagnóstico | Entre 1.000 y 1.800 |
| Guía de compra | Entre 1.300 y 2.500 |

*Una palabra = cada palabra que escribes. «Hola mundo» = 2 palabras.*

### Paso 5 — Parent ID (¿quién es el «padre»?)

- **Pilar:** déjalo vacío (no tiene padre).
- **Categoría:** pon el código del Pilar, ej. `PIL-PIEL`.
- **Artículo normal:** pon el código de la Categoría, ej. `CAT-SEN`.

Es como decir: «este artículo vive dentro de esta estantería».

### Paso 6 — links_out (¿a dónde debe enlazar ESTA página?)

Aquí pones los **códigos** de las páginas a las que quieres mandar al lector **desde este artículo**.

Puedes escribirlos así (copia y pega):

```
POS-SEN-02, POS-SEN-03, CAT-SEN
```

O así:

```
["POS-SEN-02","POS-SEN-03","CAT-SEN"]
```

**Mínimo en artículos normales:** al menos **2** códigos.

Piensa: «Si alguien lee esto, ¿qué debería leer después?»

### Paso 7 — links_in (¿quién debería enlazar HACIA aquí?)

Es **planificación**. Anota los códigos de páginas que más adelante deberían poner un enlace a este artículo.

Ejemplo:

```
CAT-SEN, POS-SEN-04
```

Tú (o quien escriba) tendrá que **poner ese enlace a mano** dentro del texto de esas otras páginas.

### Paso 8 — E-commerce (¿lleva a la tienda?)

Marca la casilla **«Decisión tomada sobre enlace comercial»** siempre en categorías y artículos normales.

Luego:

- Si **sí** enlaza a tienda: escribe el nombre del destino (ej. «Categoría sensaciones») y, si quieres, la URL de la tienda.
- Si **no** enlaza: escribe «Sin enlace comercial» en el destino.

### Paso 9 — anchor_texts (cómo se llamará el enlace)

Aquí defines **el texto azul clickeable** que verá el lector. Debe ser claro, no genérico.

**Copia este ejemplo y cámbialo:**

```json
[
  {"target": "POS-SEN-02", "anchor": "comparativa entre sensación e irritación"}
]
```

- `target` = código de la página destino.
- `anchor` = frase descriptiva del enlace.

**No uses nunca:**

- «haz clic aquí»
- «ver más»
- «leer más»
- «pincha aquí»

**Sí usa frases que digan a dónde vas:**

- «guía para elegir crema hidratante»
- «síntomas de irritación en piel sensible»

### Paso 10 — Guarda y mira los avisos

Pulsa **Actualizar**.

- **Rojo / error:** algo importante falta (tipo, código, padre, etc.). Corrígelo.
- **Amarillo / aviso:** sugerencia (texto muy corto o muy largo). Puedes publicar, pero conviene ajustar.

Abre **«Vista previa brief JSON»** en la misma caja si quieres ver el resumen automático.

---

## Ejemplo completo (copiar y adaptar)

**Artículo:** «Qué es el efecto cálido»

| Campo | Valor |
|-------|-------|
| ID de arquitectura | `POS-SEN-01` |
| Tipo | Informativo |
| Parent ID | `CAT-SEN` |
| links_out | `CAT-SEN, POS-SEN-02, ING-03` |
| links_in | `CAT-SEN, POS-SEN-04` |
| E-commerce | Marcado + destino «Categoría sensaciones» |
| anchor_texts | Ver JSON del paso 9 |

Después, al **escribir el artículo**, inserta enlaces en el editor con exactamente esas frases (anchor).

---

## ¿Qué hace la web sola y qué haces tú?

| Lo hace el tema | Lo haces tú |
|-----------------|-------------|
| Guarda tu plan | Escribes el artículo |
| Te avisa si falta algo | Revisas los avisos rojos antes de publicar |
| **Inserta enlaces al guardar** (si encuentra el texto del ancla en el artículo) | Escribes en el texto la frase del ancla, o dejas que el tema añada un bloque al final |
| **No deja publicar** si hay errores críticos y rellenaste la arquitectura | Corriges tipo, padre, links_out, e-commerce, etc. |
| Artículos relacionados abajo (según links_out) | Planificas la matriz en el brief |

### Enlaces automáticos (nuevo)

Al pulsar **Actualizar** o **Publicar**:

1. Busca en tu texto las frases de **anchor_texts** y las convierte en enlaces azules.
2. Si falta algún enlace planificado, añade al final un bloque **«Sigue leyendo en este tema»** con la lista.
3. También enlaza al **padre** (Parent ID) y a la **tienda** si pusiste URL comercial.

**Consejo:** escribe en el artículo la misma frase que pusiste en `anchor_texts` para que el enlace quede dentro del párrafo, no solo al final.

### Publicación bloqueada (nuevo)

Si rellenaste **tipo** o **ID** de arquitectura y hay avisos **rojos**, WordPress **no publicará** la página. Se guardará como **borrador** hasta que lo corrijas.

Los avisos **amarillos** (texto muy corto, etc.) no bloquean.

---

## Preguntas frecuentes

**¿Tengo que entender JSON?**  
No. Solo copia los ejemplos de esta guía y cambia los códigos y frases.

**¿El código (ID) lo ve el visitante?**  
No. Solo lo ves tú en WordPress.

**¿Puedo publicar si sale un aviso rojo?**  
Sí, WordPress te deja. Pero es mejor corregir antes.

**¿Dónde veo todas mis páginas planificadas?**  
**Apariencia → Matriz SEO**

**¿Los artículos relacionados de abajo salen solos?**  
Sí, si pusiste `links_out` y esos códigos existen en otras entradas. Si no, WordPress muestra artículos de la misma categoría.

**¿Qué es «interlinking»?**  
Solo significa **enlazar tus propios artículos entre sí** para que el lector (y Google) entienda la estructura.

---

## Checklist antes de publicar

- [ ] Tiene **código único** (ID)
- [ ] Tiene **tipo** correcto
- [ ] Tiene **Parent ID** (si no es Pilar)
- [ ] Tiene al menos **2 links_out** (si es artículo normal)
- [ ] Decidiste **e-commerce** (sí o no)
- [ ] Escribiste **frases de enlace** claras (no «ver más»)
- [ ] El texto tiene **longitud razonable** para su tipo
- [ ] Dentro del artículo aparecen los enlaces (automáticos o escribiendo la frase del ancla)
- [ ] No hay avisos **rojos** si quieres publicar

---

## ¿Necesitas ayuda?

1. **Apariencia → Guía: qué editar** — editar la plantilla en general  
2. **Apariencia → Guía fácil: enlaces** — esta guía dentro de WordPress  
3. **Apariencia → Matriz SEO** — ver todo tu mapa de enlaces  

*Minimal SEO Theme Premium — guía para principiantes*
