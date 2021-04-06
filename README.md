# Shopfully - Samuele Salvatico

## Index

- [Start](#start)
- [The code](#the-code)
- [Classic run](#classic-run)
- [Docker run](#docker-run)
- [Endpoints and docs](#endpoints-and-docs)
- [Example And Manual Tests](#example-and-manual-tests)

## Start

This project was developed using Ubuntu 20.04.

To start you need to have on your machine:

- Look in the composer file;
- MySql or Postgres instance if you want to run as standalone;
- [docker](https://docker.com), if you want to run it as image (tested with docker v19.03.8);

## The code

This project was developed on top of Lumen 6.x framework.

The code useful for the made request is in *app/Repositories/TreeNode/EloquentTreeNodeRepository*

All the routes, controllers, interfaces and stuffs in general were automatically generated using a package on which I am the main contributor [lumen-generator](https://github.com/cwssrl/eloquent-lumen-generator) 

## Classic run

*All the commands listed below are intended to be run into the application root folder.*

First of all you have to copy the .env.example file into a .env file.
After you have to set your correct database connection.

Install packages
```
composer install
```

Run the migrations and seed the db
```
php artisan migrate --seed
```

Run it!
```
php -S localhost:8081 -t public
```

Et voilà! You're ready to work at port 8081 of your localhost.

## Docker run

*All the commands listed below are intended to be run into the application root folder.*

First of all you have to copy the .env.example file into a .env file.
The database connection properties are already correctly set.

Then
```
docker-compose build && docker-compose up
```

And, when the containers are ready, to install packages and migrate and seed the database, please run
```
docker-compose exec samshop composer install && docker-compose exec samshop php artisan migrate --seed --no-interaction --force
```

Et voilà! You're ready to work at port 8081 of your localhost.

## Endpoints and docs

If you want to see the docs and test the APIs you can browse

```
http://localhost:8081/api/v1/documentation
```

## Example And Manual Tests

This is the workflow you can follow to test the code.

If you go to the documentation url as mentioned above you can find a fully functional swagger-client to test the repo.

The homework-requested API is the following one
```
/api/v1/tree_nodes/children
```

I've added a custom param to the optional ones: **only_direct**. If it is true it returns only data about the "direct" children, so the ones that are only in the following lower level from the requested node one. Otherwise it looks for all the descendants. Default is true
