#!/bin/bash
cd "$(dirname "$0")"

echo "========================================="
echo "       MACDASH ESTA EN LINEA (MAC)       "
echo "========================================="
echo " Limpiando sesiones antiguas..."

php artisan tinker --execute="\Illuminate\Support\Facades\DB::table('sessions')->truncate(); \App\Models\User::query()->update(['remember_token' => null]);" > /dev/null 2>&1

echo " Sistema limpio y funcionando perfecto.  "
echo "                                         "
echo " PARA APAGAR EL SISTEMA:                 "
echo " Simplemente CIERRA ESTA VENTANA NEGRA.  "
echo "========================================="

# Espera 2 segundos en el fondo y abre el navegador PREDETERMINADO de la Mac
(sleep 3 && open http://127.0.0.1:8000) &

# Ejecutamos Laravel
php artisan serve