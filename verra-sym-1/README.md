# Prueba Técnica

Solución desarrollada bajo arquitectura limpia, aplicando principios **SOLID** y **Domain-Driven Design (DDD)** para garantizar la separación de conceptos, mantenibilidad y alto rendimiento.

## Requisitos Previos

- PHP 8.4.2 o superior
- Composer

## Instalación y Configuración

1. Clonar el repositorio.
2. Instalar las dependencias de Composer:
   ```bash
   composer install
   ```

## Ejecución del Servidor Local

Para iniciar el servidor web nativo de PHP:
```bash
php -S localhost:8000 -t public
```
La API estará disponible en: `http://localhost:8000`

## Ejecución de Pruebas Automatizadas

La suite incluye pruebas unitarias para el dominio y pruebas de integración para la API:
```bash
php bin/phpunit
```
