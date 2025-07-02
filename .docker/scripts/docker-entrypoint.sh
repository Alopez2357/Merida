#!/bin/bash
set -ex

APP_DIR="/var/www/localhost/htdocs/app"

# Ejecutar Composer para instalar librerias solo si no existe la carpeta "vendor".
AUX="$APP_DIR/vendor"

if [ ! -d "$AUX" ]; then
  composer install -d $APP_DIR
fi

# Crear el archivo . env si no existe y generar la key de laravel.
AUX="$APP_DIR/.env"

if [ ! -f "$AUX" ]; then
  cp $APP_DIR/.env.example $AUX

  php $APP_DIR/artisan key:generate
fi

# Clean old process
rm -fr /run/apache2/httpd.pid

# Starting Apache
/usr/sbin/httpd -D FOREGROUND
