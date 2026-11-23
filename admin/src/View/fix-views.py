import re
from pathlib import Path

# Skript se spustí ve složce, kde se právě nachází (src/View)
target_dir = Path(".")

# Regex pro import (vyhledání)
use_pattern = re.compile(
    r'use\s+Joomla\\CMS\\MVC\\View\\HtmlView\s*;',
    re.IGNORECASE
)

# Regex pro deklaraci třídy (vyhledání libovolného jména třídy)
class_pattern = re.compile(
    r'\bclass\s+[A-Za-z0-9_]+\s+extends\s+HtmlView\b',
    re.IGNORECASE
)

modified_count = 0
print(f"Prohledávám složku: {target_dir.resolve()}\n")

for file_path in target_dir.rglob("*.php"):
    content = file_path.read_text(encoding="utf-8")
    original_content = content

    # 1. Úprava use importu (lambda zabrání chybě se zpětnými lomítky v Pythonu 3.13)
    content = use_pattern.sub(
        lambda m: "use Joomla\\CMS\\MVC\\View\\HtmlView AS BaseHtmlView;",
        content
    )

    # 2. Úprava deklarace třídy
    content = class_pattern.sub("class HtmlView extends BaseHtmlView", content)

    # Uložení pouze pokud došlo ke změně
    if content != original_content:
        file_path.write_text(content, encoding="utf-8")
        print(f"Upraven: {file_path}")
        modified_count += 1

print(f"\nHotovo! Celkem upraveno souborů: {modified_count}")
