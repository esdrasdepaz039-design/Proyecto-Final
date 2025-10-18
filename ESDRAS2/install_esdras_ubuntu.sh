#!/bin/bash

# Script de instalación para ESDRAS en XAMPP (Ubuntu)
# Este script automatiza la instalación del sistema ESDRAS en un entorno XAMPP en Ubuntu

# Colores para mensajes
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para mostrar mensajes
show_message() {
    echo -e "${GREEN}[ESDRAS]${NC} $1"
}

show_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

show_warning() {
    echo -e "${YELLOW}[AVISO]${NC} $1"
}

# Verificar si se está ejecutando como root
if [ "$EUID" -ne 0 ]; then
    show_error "Este script debe ejecutarse como root (sudo)."
    exit 1
fi

# Verificar si XAMPP está instalado
if [ ! -d "/opt/lampp" ]; then
    show_error "XAMPP no está instalado en la ruta predeterminada (/opt/lampp)."
    show_message "Instalando XAMPP..."
    
    # Descargar XAMPP
    wget https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/8.2.4/xampp-linux-x64-8.2.4-0-installer.run -O /tmp/xampp-installer.run
    
    # Hacer el instalador ejecutable
    chmod +x /tmp/xampp-installer.run
    
    # Ejecutar el instalador
    /tmp/xampp-installer.run --mode unattended
    
    # Verificar si la instalación fue exitosa
    if [ ! -d "/opt/lampp" ]; then
        show_error "La instalación de XAMPP falló. Por favor, instálelo manualmente."
        exit 1
    else
        show_message "XAMPP instalado correctamente."
    fi
else
    show_message "XAMPP ya está instalado."
fi

# Iniciar servicios de XAMPP
show_message "Iniciando servicios de XAMPP..."
/opt/lampp/lampp start

# Verificar si PHP está disponible
if ! command -v php &> /dev/null; then
    show_warning "PHP no está disponible en el PATH del sistema."
    show_message "Creando enlace simbólico para PHP..."
    ln -sf /opt/lampp/bin/php /usr/local/bin/php
fi

# Verificar si Composer está instalado
if ! command -v composer &> /dev/null; then
    show_message "Instalando Composer..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    php -r "unlink('composer-setup.php');"
    
    if ! command -v composer &> /dev/null; then
        show_error "La instalación de Composer falló. Por favor, instálelo manualmente."
        exit 1
    else
        show_message "Composer instalado correctamente."
    fi
else
    show_message "Composer ya está instalado."
fi

# Configurar el directorio del proyecto
PROJECT_DIR="/opt/lampp/htdocs/esdras"
show_message "Configurando el directorio del proyecto en $PROJECT_DIR..."

# Crear el directorio si no existe
if [ ! -d "$PROJECT_DIR" ]; then
    mkdir -p "$PROJECT_DIR"
fi

# Copiar archivos del proyecto
show_message "Copiando archivos del proyecto..."
cp -r $(dirname "$0")/* "$PROJECT_DIR/"

# Establecer permisos
show_message "Estableciendo permisos..."
chmod -R 755 "$PROJECT_DIR"
chmod -R 777 "$PROJECT_DIR/writable"

# Instalar dependencias con Composer
show_message "Instalando dependencias con Composer..."
cd "$PROJECT_DIR"
composer install --no-dev

# Configurar la base de datos
show_message "Configurando la base de datos..."

# Crear la base de datos y el usuario
DB_NAME="esdras_library"
DB_USER="root"
DB_PASS=""

# Crear la base de datos
/opt/lampp/bin/mysql -u "$DB_USER" -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"

# Importar el esquema y datos iniciales
show_message "Importando esquema y datos iniciales..."
/opt/lampp/bin/mysql -u "$DB_USER" "$DB_NAME" < "$PROJECT_DIR/esdras_library_completo.sql"

# Configurar el archivo .env
show_message "Configurando el archivo .env..."
cp "$PROJECT_DIR/env" "$PROJECT_DIR/.env"

# Actualizar la configuración de la base de datos en .env
sed -i "s/database.default.hostname = localhost/database.default.hostname = 127.0.0.1/g" "$PROJECT_DIR/.env"
sed -i "s/database.default.database = esdras_library/database.default.database = $DB_NAME/g" "$PROJECT_DIR/.env"
sed -i "s/database.default.username = root/database.default.username = $DB_USER/g" "$PROJECT_DIR/.env"
sed -i "s/database.default.password = /database.default.password = $DB_PASS/g" "$PROJECT_DIR/.env"

# Actualizar la configuración de la base de datos en Database.php
show_message "Actualizando configuración de Database.php..."
sed -i "s/'hostname'     => 'localhost',/'hostname'     => '127.0.0.1',/g" "$PROJECT_DIR/app/Config/Database.php"

# Configurar el host virtual (opcional)
show_message "¿Desea configurar un host virtual para ESDRAS? (s/n)"
read -r configure_vhost

if [ "$configure_vhost" = "s" ] || [ "$configure_vhost" = "S" ]; then
    show_message "Configurando host virtual..."
    
    # Crear archivo de configuración del host virtual
    cat > /opt/lampp/etc/extra/httpd-vhosts.conf << EOF
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot "/opt/lampp/htdocs/esdras/public"
    ServerName esdras.local
    ServerAlias www.esdras.local
    <Directory "/opt/lampp/htdocs/esdras/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog "logs/esdras-error_log"
    CustomLog "logs/esdras-access_log" common
</VirtualHost>
EOF
    
    # Asegurarse de que la configuración de vhosts esté habilitada
    if ! grep -q "Include etc/extra/httpd-vhosts.conf" /opt/lampp/etc/httpd.conf; then
        echo "Include etc/extra/httpd-vhosts.conf" >> /opt/lampp/etc/httpd.conf
    fi
    
    # Agregar entrada al archivo hosts
    if ! grep -q "esdras.local" /etc/hosts; then
        echo "127.0.0.1 esdras.local www.esdras.local" >> /etc/hosts
    fi
    
    # Reiniciar XAMPP
    show_message "Reiniciando XAMPP..."
    /opt/lampp/lampp restart
    
    show_message "Host virtual configurado. Puede acceder al sistema en http://esdras.local"
else
    show_message "Puede acceder al sistema en http://localhost/esdras/public"
fi

# Mensaje final
show_message "¡Instalación completada!"
show_message "Credenciales de acceso:"
show_message "Usuario: biblioteca"
show_message "Contraseña: biblioteca123"
show_message "URL de acceso: http://localhost/esdras/public o http://esdras.local (si configuró el host virtual)"