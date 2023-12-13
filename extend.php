<?php

/*
 * This file is part of mattoid/flarum-ext-money-history.
 *
 * Copyright (c) 2023 mattoid.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Flarum\Extend;

use Mattoid\MoneyHistory\Api\Controller\ListUserMoneyHistoryController;
use Mattoid\MoneyHistory\Event\MoneyAllHistoryEvent;
use Mattoid\MoneyHistory\Listeners\MoneyAllHistoryListeners;
use Mattoid\MoneyHistory\Listeners\MoneyHistoryListeners;
use Mattoid\MoneyHistory\Event\MoneyHistoryEvent;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less')
        ->route('/u/{username}/money/history', 'mattoid-money-history.forum.nav'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Routes('api'))
        ->get('/users/{id}/money/history', 'user.money.history', ListUserMoneyHistoryController::class),

    (new Extend\Event())
        ->listen(MoneyHistoryEvent::class, MoneyHistoryListeners::class)
        ->listen(MoneyAllHistoryEvent::class, MoneyAllHistoryListeners::class),
];
