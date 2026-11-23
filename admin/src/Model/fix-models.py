import re
from pathlib import Path

# Runs in current directory (e.g., src/Model)
target_dir = Path(".")

# Regex matching "class <OldClassName> extends/implements/{"
class_pattern = re.compile(
    r'(\bclass\s+)[A-Za-z0-9_]+(\s+extends|\s+implements|\s*\{)',
    re.IGNORECASE
)

modified_count = 0
print(f"Scanning directory: {target_dir.resolve()}\n")

for file_path in target_dir.rglob("*.php"):
    if file_path.name == "fix-models.py":
        continue

    # Get class name directly from the filename (e.g. 'CatModel' from 'CatModel.php')
    expected_class_name = file_path.stem

    content = file_path.read_text(encoding="utf-8")
    original_content = content

    # Replace class name safely using lambda callback
    content = class_pattern.sub(
        lambda m, cn=expected_class_name: f"{m.group(1)}{cn}{m.group(2)}",
        content
    )

    # Save only if modified
    if content != original_content:
        file_path.write_text(content, encoding="utf-8")
        print(f"Updated: {file_path.name} -> class {expected_class_name}")
        modified_count += 1

print(f"\nDone! Total files updated: {modified_count}")
