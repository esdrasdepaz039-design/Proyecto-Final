#!/bin/bash

# Colores para la salida
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Verificar si se está ejecutando como root
if [ "$(id -u)" -ne 0 ]; then
    echo -e "${RED}Este script debe ejecutarse como root. Usa: sudo ./install_esdras.sh${NC}"
    exit 1
fi

echo -e "${GREEN}Iniciando la instalación de la aplicación ESDRAS...${NC}"

# 1. Actualizar el sistema
echo -e "${GREEN}Actualizando el sistema...${NC}"
apt-get update
apt-get upgrade -y

# 2. Instalar dependencias
echo -e "${GREEN}Instalando dependencias...${NC}"
apt-get install -y wget nano unzip git

# 3. Instalar XAMPP si no está instalado
if [ ! -d "/opt/lampp" ]; then
    echo -e "${GREEN}Instalando XAMPP...${NC}"
    wget https://www.apachefriends.org/xampp-files/8.2.12/xampp-linux-x64-8.2.12-0-installer.run -O xampp-installer.run
    chmod +x xampp-installer.run
    ./xampp-installer.run --mode unattended
    rm xampp-installer.run
else
    echo -e "${GREEN}XAMPP ya está instalado.${NC}"
fi

# 4. Iniciar XAMPP
echo -e "${GREEN}Iniciando XAMPP...${NC}"
/opt/lampp/lampp start

# 5. Configurar la base de datos
echo -e "${GREEN}Configurando la base de datos...${NC}"
if [ -f "esdras_library.sql" ]; then
    /opt/lampp/bin/mysql -u root -e "CREATE DATABASE IF NOT EXISTS esdras_library;"
    /opt/lampp/bin/mysql -u root esdras_library < esdras_library.sql
    echo -e "${GREEN}Base de datos importada correctamente.${NC}"
else
    echo -e "${RED}Error: No se encontró el archivo esdras_library.sql${NC}"
    exit 1
fi

# 6. Mover la aplicación al directorio de XAMPP
echo -e "${GREEN}Configurando la aplicación...${NC}"
APP_DIR="/opt/lampp/htdocs/esdras"
mkdir -p $APP_DIR
cp -r . $APP_DIR

# 7. Configurar permisos
chown -R www-data:www-data $APP_DIR
chmod -R 755 $APP_DIR/writable

# 8. Configurar archivo .env
cp $APP_DIR/env $APP_DIR/.env
sed -i "s|^app.baseURL = .*|app.baseURL = 'http://localhost/esdras/public/'|" $APP_DIR/.env
sed -i "s/^database.default.hostname = .*/database.default.hostname = localhost/" $APP_DIR/.env
sed -i "s/^database.default.database = .*/database.default.database = esdras_library/" $APP_DIR/.env
sed -i "s/^database.default.username = .*/database.default.username = root/" $APP_DIR/.env
sed -i "s/^database.default.password = .*/database.default.password = /" $APP_DIR/.env

# 9. Configurar virtual host
echo -e "${GREEN}Configurando virtual host...${NC}"
VHOST_FILE="/opt/lampp/etc/extra/httpd-vhosts.conf"
if ! grep -q "esdras.local" $VHOST_FILE; then
    cat >> $VHOST_FILE <<EOL

<VirtualHost *:80>
    DocumentRoot "/opt/lampp/htdocs/esdras/public"
    ServerName esdras.local
    <Directory "/opt/lampp/htdocs/esdras/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOL
fi

# 10. Configurar hosts
echo "127.0.0.1   esdras.local" >> /etc/hosts

# 11. Habilitar mod_rewrite
sed -i 's/#LoadModule rewrite_module/LoadModule rewrite_module/' /opt/lampp/etc/httpd.conf

# 12. Instalar Composer si no está instalado
if ! command -v composer &> /dev/null; then
    echo -e "${GREEN}Instalando Composer...${NC}"
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    php -r "unlink('composer-setup.php');"
fi

# 13. Instalar dependencias de Composer
echo -e "${GREEN}Instalando dependencias de Composer...${NC}"
cd $APP_DIR
php /usr/local/bin/composer install --no-interaction

# 14. Reiniciar XAMPP
echo -e "${GREEN}Reiniciando XAMPP...${NC}"
/opt/lampp/lampp restart

# 15. Mostrar información de acceso
echo -e "\n${GREEN}¡Instalación completada!${NC}"
echo -e "\nPuedes acceder a tu aplicación en:"
echo -e "- http://localhost/esdras/public"
echo -e "- http://esdras.local"
echo -e "\n${RED}Nota:${NC} Si usas el nombre de dominio esdras.local, asegúrate de haberlo agregado a tu archivo /etc/hosts"

exit 0
