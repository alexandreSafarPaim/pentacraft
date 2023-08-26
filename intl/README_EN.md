# Pentacraft: Library for fast development of a REST API in Laravel

Pentacraft is a powerful and efficient library designed to facilitate the creation of REST APIs in Laravel, allowing you to develop projects in an agile and efficient way. With a host of useful commands and out-of-the-box features, Pentacraft simplifies the process of creating APIs, allowing you to focus on what really matters: your application logic.

> Author: Alexandre Safar Paim

> Development model: Pentagrama Consulting and Systems

> Current release: v1.3.2

***[**&rarr; Documentation in portuguese**](../README.md)***

## Main Feature:

- **Simplified Commands**: Pentacraft provides a collection of commands that speed up the process of creating controllers, models, migrations and more. With just a few commands, you can quickly define all the necessary structure for your API.

- **Editable Templates**: Pentacraft provides Model and Controller templates that can be edited to suit your needs. You can add new methods, properties and more.


## Installation

To start using Pentacraft, follow this step:

```bash
composer require alexandresafarpaim/pentacraft
```

## Commands

```bash
php artisan pcraft:model <model name> {-m | --migration} {-c | --controller} {-s | --soft}
```
> -m or --migration: Create the Migration

> -c or --controller: Create the Controller

> -s or --soft: Add softDeletes to the Model

```bash
php artisan pcraft:controller <controller name> {-m | --mod el}
```
> -m or --model: Create the Model

The necessary files for your API will be created, including the model, migration, controller, request, resource and routes.

```bash
php artisan pcraft:remove <model name> {-m | --migration} {-c | --controller} {--controller-only} {migration-only}
```
> -m or --migration: Remove the Migration

> -c or --controller: Remove the Controller

> --controller-only: Remove only the Controller

> --migration-only: Remove only the Migration

The files created by the pcraft:model command will be removed.


## Publication of Models

```bash
php artisan vendor:publish --tag=pentacraft
```

The Model and Controller templates will be published in the ```public/pentacraft/templates``` folder of your project.

Update the new path in the .env with the variable ```PENTACRAFT_MODEL``` and ```PENTACRAFT_CONTROLLER```


## Setup the templates

Templates can be edited however you want! It has variables that will be replaced by the template creation command.

### Model

```
@@name - Model name
@@soft_import - Soft delete import
@@soft_use - Use of soft delete
```

### Controller

```
@@model_import - Model import
@@resource_import - Resource import
@@request_import - Import requests
@@controller_name - Controller name
@@model_name - Name of the model
@@model_var - Model name in variable
@@resource - Name of the resource
@@request_create - Create request name
@@request_update - Update request name
```
