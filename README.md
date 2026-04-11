# Money History

![License](https://img.shields.io/badge/license-LPL-1.02-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/mattoid/flarum-ext-money-history.svg)](https://packagist.org/packages/mattoid/flarum-ext-money-history) [![Total Downloads](https://img.shields.io/packagist/dt/mattoid/flarum-ext-money-history.svg)](https://packagist.org/packages/mattoid/flarum-ext-money-history)

A [Flarum](http://flarum.org) extension for recording and displaying user balance history.

用于记录用户资金变动数据，方便用户查看自己的资产流向信息。

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
