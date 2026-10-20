MacDash 
Sistema web de gestión de tareas diseñado específicamente para optimizar los flujos de trabajo y la administración de órdenes en una imprenta.

Características Principales
Gestión de Órdenes: Seguimiento del estado de los trabajos de impresión desde su recepción hasta la entrega.
Base de Datos Ligera: Integración con SQLite para una portabilidad total y cero configuración de servidores externos de bases de datos.
Despliegue Rápido en Windows: Incluye scripts de ejecución automática (.bat) personalizados para levantar el sistema en las computadoras de producción con un solo clic.

Tecnologías Utilizadas
Backend: PHP, Laravel
Base de Datos: SQLite
Despliegue: Windows Batch Scripts (.bat)

Instalación y Uso
Clona este repositorio:
https://github.com/CarlosR20022/macdash.git

Instala las dependencias de PHP:
composer install

Configura tu entorno y genera la clave de la aplicación:
cp .env.example .env
php artisan key:generate

Ejecuta las migraciones para preparar la base de datos:
php artisan migrate

Inicialización rápida (Windows): Ejecuta el script .bat incluido en la raíz del proyecto para iniciar el servidor local de Laravel y abrir el sistema sin usar la terminal.

Desarrollado por CarlosR20022.
