# Test 2 — Rule Based Word Classification Engine (CLI)

## Patrones de Diseño Aplicados

- **Strategy Pattern (Patrón Estrategia)**: Encapsulación de los algoritmos de filtrado e invariantes de negocio individuales para cada tipo de regla (`LengthRule`, `PrefixRule`, `PatternRule`). Esto permite que el motor evalúe criterios de forma intercambiable y totalmente desacoplada de la infraestructura [INDEX].
- **Composite Pattern (Patrón Compuesto)**: Implementación de la regla estructural compleja `AndCompositeRule`. Permite tratar colecciones de reglas ramificadas de manera uniforme bajo la misma interfaz (`RuleInterface`), ejecutando un cortocircuito lógico inmediato para optimizar el rendimiento al procesar grandes volúmenes de texto [INDEX].
- **Factory Pattern (Patrón Factoría)**: Centralización y resolución recursiva del árbol de configuración JSON mediante `RuleFactory`, encargada de instanciar la estructura exacta de objetos de dominio basándose en la configuración de entrada [INDEX].
- **Type Safety Real (Seguridad de Tipos)**: Introducción del modelo `WordCollection` y del Objeto de Valor `Word`. Esto erradica por completo la obsesión por los primitivos (*Primitive Obsession*) y el uso de arrays nativos mutables dentro del flujo central del motor [INDEX].

## Requisitos Previos

- PHP 8.4 o superior
- Composer

## Instalación y Uso

1. Accede al directorio de la prueba:
   ```bash
   cd verra-1
   ```
2. Instala el cargador de dependencias y las librerías de desarrollo:
   ```bash
   composer install
   ```
3. Ejecuta el clasificador pasándole un archivo de reglas JSON como argumento:
   ```bash
   php classify.php rules.json
   ```
   *Nota: El script requiere que el archivo de diccionario `words.txt` esté presente en la raíz de este mismo directorio [INDEX].*

## 🧪 Ejecución de la Suite de Pruebas

Se ha implementado una suite de pruebas automatizadas con PHPUnit que valida de forma aislada cada estrategia de filtrado, el mapeo de expresiones regulares del comodín `?`, el cortocircuito lógico del Composite y la seguridad de tipos de las colecciones [INDEX]:

```bash
vendor/bin/phpunit tests
```
