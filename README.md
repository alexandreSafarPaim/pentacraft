# Pentacraft: Library para Desenvolvimento Ágil de APIs REST no Laravel

O Pentacraft é uma biblioteca poderosa e eficiente desenvolvida para facilitar a criação de APIs REST no Laravel, permitindo que você desenvolva projetos de maneira ágil e eficaz. Com uma série de comandos úteis e recursos prontos para uso, o Pentacraft simplifica o processo de criação de APIs, permitindo que você se concentre no que realmente importa: a lógica do seu aplicativo.

> Autor: Alexandre Safar Paim

> Modelo de desenvolvimento: Pentagrama Consultoria e Sistemas

> Release atual: 1.1.0


## Recurso Principal:

- **Comandos Simplificados**: O Pentacraft fornece uma coleção de comandos que aceleram o processo de criação de controladores, modelos, migrações e muito mais. Com apenas alguns comandos, você pode definir rapidamente toda a estrutura necessária para sua API.


## Instalação

Para começar a utilizar o Pentacraft, siga esta etapa:

```bash
composer require alexandresafarpaim/pentacraft
```

## Comandos

```bash
php artisan pcraft:model {nome do modelo} {-m | --migration} {-c | --controller}
```

```bash
php artisan pcraft:controller {nome do controlador} {-m | --model}
```

> Serão criados os arquivos necessários para sua API, incluindo o modelo, a migração, controlador, request, resource e rotas.

___
