# Okita

Characters & routines generator

## Installation

```
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update
yarn install
yarn encore production
```

## Commands

### Create project

```
php bin/console app:generate:project <projectName>
```

### Clean project
```
php bin/console app:generate:clean <projectName>
```