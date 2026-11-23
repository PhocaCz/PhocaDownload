import re
from pathlib import Path

# Skript spustí prohledávání v aktuální složce a všech jejích podsložkách
target_dir = Path(".")

# Regex chytající "new PhocaDownloadRenderAdminview" nebo "new PhocaDownloadRenderAdminviews"
# \b na konci zajistí, že to chytí konec slova těsně před závorkou ()
pattern = re.compile(r'new\s+PhocaDownloadRender(Adminviews?)\b', re.IGNORECASE)

modified_count = 0
print(f"Prohledávám složku: {target_dir.resolve()}\n")

for file_path in target_dir.rglob("*.php"):
    # Skript ignoruje sám sebe, pokud byste ho pojmenovali .php
    if file_path.name == "fix-renders.py" or file_path.name.endswith("fix-renders.php"):
        continue

    content = file_path.read_text(encoding="utf-8")
    original_content = content

    # 1. Najdeme, jestli se v souboru vyskytuje jedna nebo obě třídy
    matches = pattern.finditer(content)
    found_classes = set()
    for m in matches:
        # Získáme zachycenou část (Adminview nebo Adminviews)
        # A zajistíme správnou velikost písmen (první velké, zbytek malé)
        cls_name = m.group(1).capitalize()
        found_classes.add(cls_name)

    if not found_classes:
        continue

    # 2. Nahradíme samotné volání v kódu
    # Původní: new PhocaDownloadRenderAdminview
    # Nové: new Adminview
    content = pattern.sub(r'new \1', content)

    # 3. Zkontrolujeme a případně vložíme "use" hlavičky
    for cls in found_classes:
        use_stmt = f"use Phoca\\Component\\Phocadownload\\Administrator\\Render\\{cls};"

        # Pokud už tam hlavička je, přeskočíme přidávání
        if use_stmt in content:
            continue

        # Hledáme vhodné místo kam hlavičku vložit (prioritně za 'namespace')
        namespace_match = re.search(r'(namespace\s+[^;]+;)', content)
        if namespace_match:
            insert_pos = namespace_match.end()
            content = content[:insert_pos] + f"\n\n{use_stmt}" + content[insert_pos:]
        else:
            # Pokud není namespace, dáme to za defined('_JEXEC')
            jexec_match = re.search(r'(defined\s*\(\s*[\'"]_JEXEC[\'"]\s*\)\s*or\s*die.*?;)', content, re.IGNORECASE)
            if jexec_match:
                insert_pos = jexec_match.end()
                content = content[:insert_pos] + f"\n\n{use_stmt}" + content[insert_pos:]
            else:
                # Jako poslední možnost to dáme hned za <?php
                php_match = re.search(r'(<\?php\s*)', content)
                if php_match:
                    insert_pos = php_match.end()
                    content = content[:insert_pos] + f"\n{use_stmt}\n" + content[insert_pos:]

    # 4. Pokud se obsah změnil, uložíme ho
    if content != original_content:
        file_path.write_text(content, encoding="utf-8")
        print(f"Upraven soubor: {file_path}")
        for c in found_classes:
            print(f"  -> Změněno na 'new {c}' a zkontrolován import")
        modified_count += 1

print(f"\nHotovo! Celkem upraveno souborů: {modified_count}")
