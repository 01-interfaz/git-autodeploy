# git-autodeploy
Autodeployer for GIT

* Valida el usuario
* Valida la aplicación
* Valida la plataforma
* Valida la rama
* Se conecta por SSH
* Navega a la ruta de la aplicación
* Ejecuta update.sh

### Configurar una clave SSH en algun perfil con acceso al repositorio

* En el servidor crea una clave SSH
* Accede a https://github.com/settings/keys

### Configurar los datos SSH en settings.php

```php
define("SSH_HOST", "");
define("SSH_PORT", "");
define("SSH_USER", "");
define("SSH_PASS", "");
```

### Este sera el token necesario para la autentificación.

```php
define("TOKEN", "");
```

# Configuración de APP
En el archivo 'webhook.json' podras definir las aplicaciones. 
Por defecto los drivers que vienen se conectaran por SSH y ejecutaran un archivo llamada 'update.sh'

```json
{
  "app.test": {
    "branch": "master",
    "path": "/var/www/prod/app-test",
    "driver": "github"
  }
}
```

* El nombre de la aplicación lo usaremos para la URL. 
* Definir que rama ('branch') sera la disparadora del evento. 
* La ruta donde se encuntra la aplicación
* El driver a utilizar, web o github

# URL

`http://{IP}:{PUERTO}/?app={APP_NAME}&token={TOKEN}`

# NGINX

```nginx
server {
    listen 8000 default_server;
    root /var/www/prod/git-autodeploy/public;

    index index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php7.4-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

# WebHook Github

content-type = application/json
secret = null
Which events would you like to trigger this webhook? = Just the push event.
