# Guía de Solución de Problemas de Conexión en ESDRAS

## Problema: "Unable to connect" al hacer login en Ubuntu

Si estás experimentando problemas de conexión al intentar hacer login en el sistema ESDRAS en Ubuntu, sigue esta guía para resolverlo.

### Solución 1: Cambiar la configuración de la base de datos

El problema más común es que MySQL en Ubuntu a veces no reconoce 'localhost' correctamente. Sigue estos pasos:

1. Abre el archivo de configuración de la base de datos:
   ```
   sudo nano /opt/lampp/htdocs/esdras/app/Config/Database.php
   ```

2. Busca la sección de configuración de la base de datos y cambia 'localhost' por '127.0.0.1':
   ```php
   public array $default = [
       'hostname'     => '127.0.0.1',  // Cambia 'localhost' por '127.0.0.1'
       'username'     => 'root',
       'password'     => '',
       'database'     => 'esdras_library',
       ...
   ```

3. Guarda el archivo y reinicia XAMPP:
   ```
   sudo /opt/lampp/lampp restart
   ```

### Solución 2: Verificar que la base de datos existe

1. Accede a phpMyAdmin en `http://localhost/phpmyadmin`
2. Verifica que la base de datos `esdras_library` existe
3. Si no existe, créala e importa el archivo SQL:
   ```
   sudo /opt/lampp/bin/mysql -u root -e "CREATE DATABASE esdras_library CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
   sudo /opt/lampp/bin/mysql -u root esdras_library < /opt/lampp/htdocs/esdras/esdras_library_completo.sql
   ```

### Solución 3: Verificar permisos de archivos

1. Asegúrate de que los permisos de los archivos sean correctos:
   ```
   sudo chmod -R 755 /opt/lampp/htdocs/esdras
   sudo chmod -R 777 /opt/lampp/htdocs/esdras/writable
   ```

### Solución 4: Verificar la configuración del archivo .env

1. Abre el archivo .env:
   ```
   sudo nano /opt/lampp/htdocs/esdras/.env
   ```

2. Asegúrate de que la configuración de la base de datos sea correcta:
   ```
   database.default.hostname = 127.0.0.1
   database.default.database = esdras_library
   database.default.username = root
   database.default.password = 
   ```

### Solución 5: Reinstalar usando el script actualizado

Si has descargado la versión actualizada del sistema, puedes reinstalarlo usando el script de instalación actualizado:

```
sudo bash install_esdras_ubuntu.sh
```

El script actualizado configura automáticamente la dirección IP '127.0.0.1' en lugar de 'localhost' para evitar problemas de conexión.

### Verificación

Después de aplicar alguna de estas soluciones, intenta acceder nuevamente al sistema en:
- http://localhost/esdras/public
- O si configuraste un host virtual: http://esdras.local

Credenciales de acceso:
- Usuario: biblioteca
- Contraseña: biblioteca123

## Contacto para soporte

Si continúas experimentando problemas después de intentar estas soluciones, por favor contacta al soporte técnico con la siguiente información:
- Versión de Ubuntu
- Versión de XAMPP
- Capturas de pantalla de cualquier mensaje de error
- Contenido del archivo de registro (log) ubicado en `/opt/lampp/htdocs/esdras/writable/logs/`