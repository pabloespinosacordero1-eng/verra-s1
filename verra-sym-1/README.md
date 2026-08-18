# Test 1 — Customer Preference Aggregation API (Symfony)

## Decisiones de Diseño y Arquitectura

- **Capa de Infraestructura (Symfony)**: El controlador actúa como un adaptador de entrada puro. Su única responsabilidad es recibir el payload HTTP, delegar la deserialización y responder al cliente. Está libre de lógica de control.
- **Capa de Aplicación (DTOs)**: Implementación de contratos de entrada fuertemente tipados (`CustomerRecordInput` y `PreferenceAggregationRequest`) que actúan como barrera protectora de tipo seguro.
- **Capa de Dominio Puro**: 
  - **Value Objects Inmutables** (`Email`, `Language`, `CategoryCollection`): Responsables de su propia normalización y validación.
  - **Aggregate Root** (`CustomerPreferenceAggregate`): Entidad central con identidad que actúa como guardiana de las invariantes de negocio. Encapsula y autogestiona el estado consolidador y la resolución de conflictos deterministas de forma semántica.
- **Type Safety Real**: Todo el flujo de ejecuciones implementa `declare(strict_types=1);` y colecciones tipadas personalizadas (`RecordCollection` y `ProfileCollection`) en lugar de matrices primitivas nativas de PHP.

## Requisitos Previos

- PHP 8.4 o superior
- Composer

## Instalación y Despliegue

1. Accede al directorio del proyecto:
   ```bash
   cd test-1-symfony
   ```
2. Instala las dependencias de Composer:
   ```bash
   composer install
   ```

## Ejecución de la Suite de Pruebas

Se ha diseñado una suite de pruebas unitarias exhaustiva que valida de forma atómica cada regla de negocio, invariante, resolución de empates alfabéticos por frecuencia y cortocircuitos lógicos:

```bash
php bin/phpunit
```

## Consumo del Endpoint API

Puedes levantar el servidor local de desarrollo de Symfony mediante:
```bash
symfony server:start
```
La API procesará las peticiones mediante el método **`POST`** en el punto de acceso:
`http://localhost:8000/api/preferences/aggregate`

### Ejemplo de Payload (JSON):
```json
{
  "records": [
    {
      "customerId": "A12",
      "email": "John.Doe+mobile@Example.com",
      "language": "EN",
      "marketingOptIn": true,
      "preferredCategories": ["tech", "books"]
    },
    {
      "customerId": "A12",
      "email": "john.doe@example.com",
      "language": "en-US",
      "marketingOptIn": false,
      "preferredCategories": ["books", "sports"]
    }
  ]
}
```
