import re
from pathlib import Path

# Skript prohledá aktuální složku a všechny její podsložky
target_dir = Path(".")

# Regex chytající: slovo "class " + "Table" + "NějakéJméno"
# Skupina 1: "class " (včetně bílých znaků)
# Skupina 2: "Cat" (vše, co následuje za slovem Table)
class_pattern = re.compile(r'(\bclass\s+)Table([A-Za-z0-9_]+)\b')

modified_count = 0
print(f"Prohledávám složku: {target_dir.resolve()}\n")

for file_path in target_dir.rglob("*.php"):
    if file_path.name == "fix-tables.py" or file_path.name.endswith("fix-tables.php"):
        continue

    content = file_path.read_text(encoding="utf-8")
    original_content = content

    # Nahrazení pomocí lambda (bezpečné pro jakékoliv speciální znaky)
    # m.group(1) = "class "
    # m.group(2) = "Cat"
    # Výsledek = "class CatTable"
    content = class_pattern.sub(
        lambda m: f"{m.group(1)}{m.group(2)}Table",
        content
    )

    # Pokud došlo ke změně, soubor se přepíše
    if content != original_content:
        file_path.write_text(content, encoding="utf-8")

        # Pro výpis do konzole najdeme, co přesně se změnilo
        matches = class_pattern.finditer(original_content)
        for m in matches:
            old_name = f"Table{m.group(2)}"
            new_name = f"{m.group(2)}Table"
            print(f"Upraven: {file_path.name} -> {old_name} změněno na {new_name}")

        modified_count += 1

print(f"\nHotovo! Celkem upraveno souborů: {modified_count}")
