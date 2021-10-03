# Pasos a seguir para instalar el proyecto
## Clonar repositorio
Para clonar el repositorio lo que haremos será escribir en el terminal la siguiente sentencia con la url del proyecto en el directorio que lo queramos:

``` bash
git clone https://github.com/ManuelGC4/symfonyBlog.git
```

También podemos usar ``` git clone ``` a través de *Visual Studio Code*, solo tenemos que darle a *View* y a *Command Palette...*, entonces se nos abrirá un caja donde solo tenemos que escribir el comando ``` git clone ```, después la url del proyecto y finalmente indicar donde queremos que nos lo guarde.

## Instalar actualizaciones
Para instalar todas las actualizaciones nos vamos al directorio del proyecto en el terminal y escribiremos la siguiente sentencia:

``` bash 
composer install
```

## Archivo de configuración de la base de datos
Necesitaremos crear nuestro archivo personalizado que conecte a nuestra base de datos, para ello haremos una copia del archivo .env y le cambiaremos el nombre a .env.local, que es el archivo que guardará la configuración de nuestra base de datos local. 
Una vez hecho esto, modificaremos el archivo cambiando la siguiente línea:

``` DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7" ```

Y cambiaremos de esta línea los siguientes datos:

- ``` db_user ```: usuario de la base de datos
- ``` db_password ```: contraseña de la base de datos
- ``` db_name ```: nombre de la base de datos

En el caso de usar sqlite o postgresql usaremos las siguientes líneas en lugar de la anterior:

- sqlite: ``` "sqlite:///%kernel.project_dir%/var/data.db" ```
- postgresql: ``` "postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=13&charset=utf8" ```

## Ejecutar migraciones
Finalmente para ejecutar las migraciones, volveremos al terminal y escribiremos la siguiente sentencia:

``` bash
php bin/console doctrine:migrations:migrate
```