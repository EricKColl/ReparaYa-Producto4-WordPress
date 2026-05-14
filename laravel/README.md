# ReparaYa · Aplicación Laravel

## Producto 3 · Migración a framework PHP MVC

Este directorio contiene la aplicación principal de **ReparaYa Producto 3**, desarrollada con **Laravel 12**.

La carpeta `laravel/` es el núcleo funcional del Producto 3. Aquí se encuentran las rutas, controladores, modelos Eloquent, vistas Blade, componentes reutilizables y configuración necesaria para ejecutar la aplicación migrada desde el Producto 2.

---

## 1. Finalidad de esta aplicación

El objetivo de esta aplicación Laravel es sustituir progresivamente la estructura PHP MVC propia del Producto 2 por una arquitectura basada en framework, más mantenible, segura y escalable.

La aplicación permite gestionar:

- Usuarios.
- Técnicos.
- Especialidades.
- Incidencias.
- Gestoras.
- Comunidades de propietarios.
- Avisos B2B.
- Liquidaciones mensuales.
- Calendarios por rol.
- API REST de servicios por zona.

---

## 2. Arquitectura general

La aplicación sigue el patrón MVC de Laravel:

```text
Petición HTTP
    ↓
routes/web.php o routes/api.php
    ↓
Controlador
    ↓
Modelo Eloquent
    ↓
Base de datos MySQL
    ↓
Vista Blade o respuesta JSON
```

Elementos principales:

```text
app/Http/Controllers/     Controladores de la aplicación
app/Models/               Modelos Eloquent
resources/views/          Vistas Blade
routes/web.php            Rutas web
routes/api.php            Rutas API
public/                   Punto de entrada público
```

---

## 3. Controladores principales

```text
AuthController.php          Login y logout de usuarios
HomeController.php          Home pública y paneles por rol
UsuarioController.php       Gestión de usuarios
TecnicoController.php       Gestión de técnicos
EspecialidadController.php  Gestión de especialidades
IncidenciaController.php    Gestión de incidencias
GestoraController.php       Gestión de gestoras
ComunidadController.php     Gestión de comunidades
B2BController.php           Login gestora, panel B2B, avisos y liquidaciones
ApiController.php           Endpoint REST /api/servicios/zonas
```

---

## 4. Modelos principales

```text
Usuario.php        Tabla usuarios
Tecnico.php        Tabla tecnicos
Especialidad.php   Tabla especialidades
Incidencia.php     Tabla incidencias
Gestora.php        Tabla gestoras
Comunidad.php      Tabla comunidades
```

Relaciones destacadas:

- Un usuario puede tener una ficha técnica.
- Un usuario puede tener varias incidencias como cliente.
- Un técnico pertenece a una especialidad.
- Una especialidad puede tener varios técnicos e incidencias.
- Una gestora puede tener varias comunidades.
- Una comunidad pertenece a una gestora.
- Una incidencia puede estar vinculada a una gestora y a una comunidad.

---

## 5. Vistas Blade principales

```text
resources/views/layouts/app.blade.php
resources/views/home.blade.php
resources/views/auth/login.blade.php
resources/views/b2b/panel.blade.php
resources/views/b2b/create_aviso.blade.php
resources/views/b2b/edit_aviso.blade.php
resources/views/components/reparaya-calendar.blade.php
resources/views/liquidaciones/index.blade.php
```

El layout principal centraliza:

- Cabecera.
- Navegación contextual.
- Sesión activa.
- Accesos según rol.
- Mensajes de éxito/error.
- Contenedor general de contenido.

---

## 6. Roles y navegación

La aplicación diferencia varios tipos de acceso:

```text
Visitante       Puede ver la home y acceder al login.
Administrador   Gestiona usuarios, técnicos, especialidades, incidencias, gestoras, comunidades y liquidaciones.
Cliente         Consulta y crea sus propias incidencias.
Técnico         Consulta sus servicios asignados.
Gestora         Accede a su panel B2B independiente.
```

La navegación se adapta según la sesión activa y el rol del usuario.

---

## 7. Bloque B2B

El bloque B2B permite que empresas administradoras de fincas trabajen dentro de ReparaYa.

Funcionalidades:

- Login independiente de gestoras.
- Panel propio de gestora.
- Creación de avisos para comunidades administradas.
- Edición y eliminación de avisos.
- Gestión de fecha y hora del servicio.
- Teléfono de contacto heredado de la comunidad.
- Estado del aviso.
- Precio base del servicio.
- Cálculo de comisión.
- Liquidaciones mensuales para administración.

---

## 8. API REST

Endpoint implementado:

```text
GET /api/servicios/zonas
```

Respuesta esperada:

```json
{
    "total_global": 1,
    "zonas": [
        {
            "zona": "Norte",
            "total_servicios": 1,
            "porcentaje": 100
        }
    ]
}
```

Este endpoint devuelve servicios agrupados por zona de la ciudad y calcula el porcentaje de cada zona respecto al total global.

---

## 9. Calendarios por rol

Se ha creado un componente reutilizable:

```text
resources/views/components/reparaya-calendar.blade.php
```

Se utiliza en:

- Home administrador.
- Home cliente.
- Home técnico.
- Panel gestora.

Cada calendario muestra únicamente los eventos correspondientes al perfil activo.

---

## 10. Configuración local

Archivo `.env` orientativo para entorno Docker local:

```env
APP_NAME=ReparaYa
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=reparaya
DB_USERNAME=root
DB_PASSWORD=rootpass

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Después de modificar `.env`:

```bash
docker exec -it daw-laravel-web sh -c "cd /app && php artisan optimize:clear"
```

---

## 11. Ejecución local

Desde la raíz del repositorio:

```bash
docker compose up -d --build
```

Comprobar servicios:

```bash
docker compose ps
```

Acceso local:

```text
http://localhost:8000
```

API local:

```text
http://localhost:8000/api/servicios/zonas
```

---

## 12. Comandos útiles

Limpiar caché:

```bash
php artisan optimize:clear
```

Listar rutas:

```bash
php artisan route:list
```

Comprobar vistas Blade:

```bash
php artisan view:cache
php artisan view:clear
```

Comprobar conexión con base de datos:

```bash
php artisan tinker --execute="echo DB::table('usuarios')->count();"
```

---

## 13. Despliegue UOC

La aplicación se ha desplegado en:

```text
https://fp064.techlab.uoc.edu/~uocx3/producto3
```

Estructura utilizada en servidor:

```text
/home/uocx3/producto3_app
/home/uocx3/public_html/producto3 -> /home/uocx3/producto3_app/public
```

La carpeta completa de Laravel queda fuera del acceso público directo. Solo se expone `public`.

---

## 14. Consideraciones importantes

No subir al repositorio:

```text
.env
vendor/
node_modules/
storage/logs/*
storage/framework/cache/*
storage/framework/views/*
storage/framework/sessions/*
```

El archivo `.env` debe configurarse de forma distinta en local y en servidor.

---

## 15. Estado final

La aplicación Laravel del Producto 3 queda funcional y preparada para revisión:

- CRUD principales operativos.
- Bloque B2B integrado.
- API REST operativa.
- Despliegue UOC realizado.
- Base de datos importada.
- Calendarios por rol integrados.
- Interfaz mejorada y navegación contextual.
