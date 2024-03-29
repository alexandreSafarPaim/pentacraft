# Pentacraft: Biblioteca para desenvolvimento rápido de uma API REST no Laravel

O Pentacraft é uma biblioteca poderosa e eficiente desenvolvida para facilitar a criação de APIs REST no Laravel, permitindo que você desenvolva projetos de maneira ágil e eficaz. Com uma série de comandos úteis e recursos prontos para uso, o Pentacraft simplifica o processo de criação de APIs, permitindo que você se concentre no que realmente importa: a lógica do seu aplicativo.

> Autor: Alexandre Safar Paim

> Modelo de desenvolvimento: Pentagrama Consultoria e Sistemas

> Release atual: v1.3.6

***[**&rarr; Documentação em inglês**](intl/README_EN.md)***

## Recurso Principal:

- **Comandos Simplificados**: O Pentacraft fornece uma coleção de comandos que aceleram o processo de criação de controladores, modelos, migrações e muito mais. Com apenas alguns comandos, você pode definir rapidamente toda a estrutura necessária para sua API.

- **Modelos Editáveis**: O Pentacraft fornece modelos de Model e Controller que podem ser editados para atender às suas necessidades. Você pode adicionar novos métodos, propriedades e muito mais.


## Instalação

Para começar a utilizar o Pentacraft, siga esta etapa:

```bash
composer require --dev alexandresafarpaim/pentacraft
```

## Comandos

```bash
php artisan pcraft:model <nome do modelo> {-m | --migration} {-c | --controller} {-s | --soft}
```
> -m ou --migration: Cria a migração do modelo

> -c ou --controller: Cria o controlador do modelo

> -s ou --soft: Adiciona o soft delete ao modelo

```bash
php artisan pcraft:controller <nome do controlador> {-m | --model}
```
> -m ou --model: Cria o modelo do controlador

Serão criados os arquivos necessários para sua API, incluindo o modelo, a migração, controlador, request, resource e rotas.

```bash
php artisan pcraft:remove <nome do modelo> {-m | --migration} {-c | --controller} {--controller-only} {migration-only}
```
> -m ou --migration: Remove a migração do modelo

> -c ou --controller: Remove o controlador do modelo

> --controller-only: Remove apenas o controlador do modelo

> --migration-only: Remove apenas a migração do modelo

Serão removidos os arquivos criados pelo comando pcraft:model


## Publicação dos Modelos

```bash
php artisan vendor:publish --tag=pentacraft
```

Serão publicados os modelos de Model e Controller na pasta ```public/pentacraft/templates``` do seu projeto.

Atualize o novo caminho no .env com a variável ```PENTACRAFT_MODEL``` e ```PENTACRAFT_CONTROLLER```


## Configurando os modelos

Os modelos podem ser editado como quiser! Ele possui variáveis que serão substituidas pelo comando de criação de modelos.

### Model

```
@@name - Nome do modelo
@@soft_import - Importação do soft delete
@@soft_use - Uso do soft delete
```

### Controller

```
@@model_import - Importação do modelo
@@resource_import - Importação do resource
@@request_import - Importação dos requests
@@controller_name - Nome do controlador 
@@model_name - Nome do modelo
@@model_var - Nome do modelo em variável
@@resource - Nome do resource
@@request_create - Nome do request de criação
@@request_update - Nome do request de atualização
```
