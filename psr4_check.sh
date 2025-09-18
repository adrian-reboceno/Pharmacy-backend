#!/bin/bash

echo "=== PSR-4 Checker & Fix Report ==="
echo ""

# Recorrer todos los archivos PHP en ./app
find ./app -type f -name "*.php" | while read -r file; do
    # Extraer namespace real desde el archivo
    namespace=$(grep -m1 "^namespace " "$file" | sed 's/namespace //' | sed 's/;//' | xargs)
    
    # Extraer clase, trait o interface
    class=$(grep -m1 -E "class |trait |interface " "$file" | awk '{print $2}')
    [ -z "$class" ] && continue

    # Nombre de archivo sin extensión
    filename=$(basename "$file" .php)

    # Comparar nombre de archivo vs clase
    if [ "$filename" != "$class" ]; then
        echo "⚠️ Clase/trait/interface '$class' en archivo '$file' no coincide con el nombre del archivo."
        echo "💡 Comando sugerido: mv \"$file\" \"$(dirname "$file")/$class.php\""
    fi

    # Construir namespace esperado desde la ruta
    # Reemplaza ./app/ por App\
    relative_path=$(dirname "$file" | sed 's|^\./app||')
    # Convertir / a \
    relative_ns=$(echo "$relative_path" | sed 's|/|\\|g')
    expected_ns="App$relative_ns"

    # Comparar namespace
    if [ "$namespace" != "$expected_ns" ]; then
        echo "⚠️ Namespace mismatch: '$namespace' en archivo '$file'"
        echo "💡 Namespace esperado: '$expected_ns'"
    fi
done

echo ""
echo "=== FIN DEL REPORTE ==="
