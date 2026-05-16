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
![YC3SWVB3DL$YERVVRTFOIQ1](https://github.com/huoxin233/flarum-ext-money-history/assets/23447157/6132bc75-f33f-4818-8c19-e413834dde1f)
![RUO SWWVYBPMG~8{Z({UU$6](https://github.com/huoxin233/flarum-ext-money-history/assets/23447157/d7ce3ad7-7912-4a2f-af4e-8b24219ba5fc)

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

## How It Works

`money-history` is no longer meant to be the primary balance-changing entry point.

For new integrations:

1. Other extensions should call the services provided by `antoinefr/flarum-ext-money`.
2. `money` writes history through the `BalanceHistoryRecorder` contract when `mattoid-money-history` is enabled.
3. `money-history` provides the `BalanceHistoryRecorder` implementation and persists the history rows.

This keeps balance mutation and history recording aligned.

## Recommended Integration For Other Extensions

If your extension needs to change user balance, inject:

```php
use AntoineFr\Money\Service\BalanceManager;
```

Then use one of these methods:

- `adjustBalance()` for one user
- `adjustBalances()` for bulk updates
- `transferBalance()` for user-to-user transfers
- `syncPersistedBalanceChange()` only when your extension already changed and saved the balance itself inside its own transaction

Example:

```php
$this->balances->adjustBalance(
    $user,
    -12.5,
    'MYEXTENSION',
    'vendor-my-extension.forum.history.purchase',
    [
        'itemTitle' => 'VIP Badge',
        'itemTypeKey' => 'vendor-my-extension.forum.item-type.badge',
    ],
    $actor
);
```

## Important Notes For Integrators

- New integrations should not depend on `money-history-auto`.
- If you already persist balance manually in the same transaction as other domain writes, call `syncPersistedBalanceChange()` after the save, with `balanceBefore` and `balanceAfter` context handled by the money service call.
- Keep `sourceParams` flat. Do not store nested JSON objects.
- Use stable translation keys in `source_key`.
- Use `source` as a stable machine-readable source name, for example `MONEYREWARDS` or `DECORATIONSTORE`.

## `source_key` And `source_params`

History reasons are rendered on the forum from:

- `source_key`
- `source_params`

The backend should store translation keys and raw parameters, not ready-to-display text.

Recommended conventions:

- plain values: `postNumber`, `itemTitle`, `username`, `orderId`
- translated values: keys ending with `Key`, for example `purchaseTypeKey`
- clickable links: keys ending with `LinkHref`, for example `postLinkHref`, `userLinkHref`

Example:

```php
[
    'itemTitle' => 'VIP Badge',
    'purchaseTypeKey' => 'vendor-my-extension.forum.purchase-type.monthly',
    'postLinkHref' => '/d/12/34',
]
```

Then the frontend translation may use:

```yml
vendor-my-extension:
  forum:
    history:
      purchase: 'Purchased <postLink>{itemTitle}</postLink> ({purchaseType})'
```

## Performance Notes

- `money-history` itself only writes history records when invoked by the money extension recorder integration.
- Avoid custom per-row history writes when a batch operation can use `adjustBalances()`.
- Avoid fetching extra models just to render history reasons. Put the needed flat values in `source_params`.

## Links

- [Packagist](https://packagist.org/packages/mattoid/flarum-ext-money-history)
- [GitHub](https://github.com/mattoid/flarum-ext-money-history)
- [Discuss](https://discuss.flarum.org/d/PUT_DISCUSS_SLUG_HERE)
