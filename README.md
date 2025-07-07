# Plataforma de Entrenamiento Funcional Online

Sistema web para la gestión de entrenamientos funcionales entre entrenadores y entrenados.

## Características

- Dos tipos de usuarios: entrenadores y entrenados
- Los entrenadores pueden:
  - Gestionar sus entrenados
  - Crear y asignar entrenamientos
  - Ver el progreso de sus entrenados
- Los entrenados pueden:
  - Ver sus entrenamientos asignados
  - Seguir las instrucciones de los ejercicios
  - Ver su historial de entrenamientos

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensión mysqli de PHP

## Instalación

1. Clonar el repositorio en tu servidor web:
```bash
git clone [URL del repositorio]
```

2. Crear la base de datos usando el script SQL:
```bash
mysql -u root -p < database/schema.sql
```

3. Configurar la conexión a la base de datos:
   - Abrir `config/database.php`
   - Modificar las constantes según tu configuración:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'tu_usuario');
     define('DB_PASS', 'tu_contraseña');
     define('DB_NAME', 'entrenamiento_funcional');
     ```

4. Configurar el servidor web:
   - Asegurarse de que el documento root apunte al directorio del proyecto
   - Configurar el módulo de reescritura de URL (mod_rewrite para Apache)

## Estructura del Proyecto

```
├── config/
│   └── database.php
├── controllers/
│   ├── AuthController.php
│   ├── BaseController.php
│   └── EntrenamientoController.php
├── models/
│   ├── BaseModel.php
│   ├── EntrenamientoModel.php
│   └── UsuarioModel.php
├── views/
│   ├── auth/
│   │   ├── login.php
│   │   └── registro.php
│   ├── entrenamientos/
│   │   ├── crear.php
│   │   └── ver.php
│   └── layouts/
│       └── main.php
├── database/
│   └── schema.sql
└── index.php
```

## Uso

1. Acceder a la aplicación a través del navegador
2. Registrar un nuevo usuario (entrenador o entrenado)
3. Iniciar sesión
4. Los entrenadores pueden comenzar a crear entrenamientos y asignarlos a sus entrenados
5. Los entrenados pueden ver y seguir sus entrenamientos asignados

## Seguridad

- Las contraseñas se almacenan hasheadas usando password_hash()
- Se utilizan consultas preparadas para prevenir SQL injection
- Se implementa control de acceso basado en roles
- Se validan y sanitizan todas las entradas de usuario

## Contribuir

1. Fork el repositorio
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles. 