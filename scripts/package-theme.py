#!/usr/bin/env python3
"""
Empaquetar Minimal SEO Theme Premium para distribución WordPress.

Uso (desde la raíz del tema o desde scripts/):
    python scripts/package-theme.py
"""

import os
import zipfile

ZIP_FILENAME = "minimal-seo-theme.zip"
EXCLUDE_DIRS = {".git", "node_modules", ".vscode", "__pycache__", ".cursor", "scripts"}
EXCLUDE_FILES = {ZIP_FILENAME}


def should_pack(filename: str) -> bool:
    if filename in EXCLUDE_FILES:
        return False
    if filename.endswith(".zip"):
        return False
    return True


def main() -> None:
    script_dir = os.path.dirname(os.path.abspath(__file__))
    theme_dir = os.path.dirname(script_dir)
    themes_dir = os.path.dirname(theme_dir)
    zip_path = os.path.join(themes_dir, ZIP_FILENAME)

    if not os.path.isdir(theme_dir):
        raise SystemExit(f"No se encontró la carpeta del tema: {theme_dir}")

    with zipfile.ZipFile(zip_path, "w", zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(theme_dir):
            dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS]
            for file in files:
                if not should_pack(file):
                    continue
                file_path = os.path.join(root, file)
                arcname = os.path.relpath(file_path, start=themes_dir).replace("\\", "/")
                zipf.write(file_path, arcname)

    size_kb = os.path.getsize(zip_path) / 1024
    print(f"ZIP generado: {zip_path}")
    print(f"Tamaño: {size_kb:.1f} KB")
    print("Listo para subir en WordPress: Apariencia > Temas > Subir tema.")


if __name__ == "__main__":
    main()
