@echo off
echo =========================================
echo       MACDASH ESTA EN LINEA (WINDOWS)
echo =========================================
echo  El sistema esta funcionando perfecto.
echo.
echo  PARA APAGAR EL SISTEMA:
echo  Simplemente CIERRA ESTA VENTANA NEGRA.
echo =========================================

:: Abrimos el navegador
start http://127.0.0.1:8000

:: Ejecutamos Laravel
php artisan serve