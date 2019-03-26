# Okita

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gdarquie/okita/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gdarquie/okita/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/gdarquie/okita/badges/build.png?b=master)](https://scrutinizer-ci.com/g/gdarquie/okita/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/gdarquie/okita/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

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