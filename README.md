# Key Metrix (SGDK) - Sistema de Gestión de Desempeño por KPIs

Aplicación web empresarial para la gestión, monitoreo y análisis de Indicadores Clave de Desempeño (KPI).

## Características Principales

- **Autenticación y Control de Acceso**: Login con roles y permisos
- **Gestión de Usuarios**: CRUD de usuarios con asignación de roles
- **Gestión de Roles y Permisos**: Control granular de acceso
- **Gestión de Áreas**: Organización por áreas funcionales
- **Gestión de Indicadores KPI**: Configuración de indicadores, fórmulas, unidades y periodicidad
- **Gestión de Metas**: Definición de metas, umbrales mínimo/máximo por periodo
- **Registro de Resultados**: Captura y seguimiento de valores reales
- **Dashboard**: Visualización consolidada con indicadores clave
- **Reportes**: Análisis y generación de reportes
- **Auditoría**: Trazabilidad de acciones en el sistema
- **Alertas**: Notificaciones por desviación de metas

## Tecnologías

- PHP 8.x
- MySQL/MariaDB
- Bootstrap 5.3
- Bootstrap Icons
- JavaScript

## Instalación

1. Copiar el proyecto a `C:\xamppp\htdocs\Proyecto Adso 44\`
2. Importar la base de datos desde `sql/database.sql` en phpMyAdmin
3. Configurar conexión en `config/database.php`
4. Acceder a `http://localhost/Proyecto%20Adso%2044/`

## Credenciales por Defecto

- **Usuario:** admin
- **Contraseña:** admin123

## Estructura del Proyecto

```
├── assets/          # Recursos estáticos (CSS, JS, imágenes)
├── auth/            # Autenticación (login, logout)
├── config/          # Configuración de base de datos
├── includes/        # Funciones y utilidades
├── modules/         # Módulos del sistema
│   ├── usuarios/
│   ├── roles/
│   ├── areas/
│   ├── indicadores/
│   ├── metas/
│   ├── resultados/
│   ├── dashboard/
│   ├── reportes/
│   ├── alertas/
│   ├── auditoria/
│   └── copias/
└── sql/             # Scripts de base de datos
```

## Basado en

Especificación IEEE 830 - KEY METRIX (SGDK)
