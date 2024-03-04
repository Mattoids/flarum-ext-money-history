# Money History 资金记录

![License](https://img.shields.io/github/license/Mattoids/flarum-ext-money-history) [![Latest Stable Version](https://img.shields.io/packagist/v/mattoid/flarum-ext-money-history.svg)](https://packagist.org/packages/mattoid/flarum-ext-money-history) [![Total Downloads](https://img.shields.io/packagist/dt/mattoid/flarum-ext-money-history.svg)](https://packagist.org/packages/mattoid/flarum-ext-money-history)

A [Flarum](http://flarum.org) extension to record income and expenses of the users' money, allowing users to track any changes made to their money.

一个 [Flarum](http://flarum.org) 扩展， 用于记录用户的资金进出，以便于用户查看自己的资金流向信息。

#### Please note: This extension does not automatically record transactions. Other extensions need to actively notify this extension of transaction events to record them. Before other extensions are adapted to work with this extension, you can use [Money History Auto](https://github.com/Mattoids/flarum-ext-money-history-auto) extension for automatic recording.

#### 注意：该插件不会自动记录，需要插件主动通知本插件的记录事件来完成消费。在其他插件为适配本插件之前，可以使用 [Money History Auto](https://github.com/Mattoids/flarum-ext-money-history-auto) 插件来实现自动记录功能。

## Problem
This extension has been developed and tested only on Chinese forums and did not take into account that there will be multiple languages on the forums at the same time. Therefore, there may be problems when being used on multilingual forums. PRs are welcomed!😊

本插件仅在中文论坛上进行开发与测试，并未考虑到在论坛上同时存在多种语言的情况，因此在多语言论坛上可能存在问题。欢迎PR！😊

## Screenshots

## Installation

Install with composer:

```sh
composer require mattoid/flarum-ext-money-history:"*"
```

## Updating

```sh
composer update mattoid/flarum-ext-money-history:"*"
php flarum migrate
php flarum cache:clear
```

## Links

- [Packagist](https://packagist.org/packages/mattoid/flarum-ext-money-history)
- [GitHub](https://github.com/mattoid/flarum-ext-money-history)
- [Discuss](https://discuss.flarum.org/d/PUT_DISCUSS_SLUG_HERE)
