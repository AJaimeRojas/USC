Administrador de contenido - Base
=============

Framework: Symfony

Complementos:

* [Control de usuarios](https://github.com/FriendsOfSymfony/FOSUserBundle)

Inicio de creación de administrador de contenidos

```shell
# Iniciar con nuevo proyecto symfony
composer install
composer update

# iniciar servidor localhost:8000
php app/console server:start

# detener servidor
php app/console server:stop

# crear bundle:
php app/console generate:bundle

# crear entity:
php app/console generate:doctrine:entity

# crear entity desde un archivo yml existente:
php app/console generate:doctrine entities AppBundle

# sincronizar base de datos con la entity actual:
php app/console doctrine:schema:update --force

# crear nuevo controlador:
php app/console generate:controller

# crear usuario admin
php app/console fos:user:create admin --super-admin
```
