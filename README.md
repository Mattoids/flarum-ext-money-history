# Money History

![License](https://img.shields.io/badge/license-LPL-1.02-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/mattoid/flarum-ext-money-history.svg)](https://packagist.org/packages/mattoid/flarum-ext-money-history) [![Total Downloads](https://img.shields.io/packagist/dt/mattoid/flarum-ext-money-history.svg)](https://packagist.org/packages/mattoid/flarum-ext-money-history)

A [Flarum](http://flarum.org) extension. money history

用于记录用户资金消费数据，以便于用户查看自己的资产流向信息。
### 注意：该插件不会自动记录，需要插件主动通知本插件的记录事件来完成消费。在其他插件为适配本插件之前，可以使用 [money history auto](https://github.com/Mattoids/flarum-ext-money-history-auto) 插件来实现自动记录功能

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
