@echo off
echo =========================================
echo       INICIANDO MACDASH...
echo =========================================
echo  Por favor espera unos segundos.

:: 1. Ejecuta Laragon (Asumiendo que se instaló en la ruta por defecto de Windows)
start "" "C:\laragon\laragon.exe"

:: 2. Pausa el sistema 5 segundos para darle tiempo a Laragon de encender Apache
timeout /t 6 /nobreak > NUL

:: 3. Abre el navegador predeterminado en el dominio automático de Laragon
:: (Asegúrate de que la carpeta del proyecto se llame exactamente "macdash" dentro de www)
start http://macdash.test

:: 4. Cierra esta consola negra para que no estorbe
exit