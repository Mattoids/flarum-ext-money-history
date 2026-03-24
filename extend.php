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
use Flarum\Api\Serializer\BasicUserSerializer;
use AntoineFr\Money\Event\MoneyUpdated;

use Mattoid\MoneyHistory\Api\Controller\ListUserMoneyHistoryController;
use Mattoid\MoneyHistory\Attributes\UserAttributes;
use Mattoid\MoneyHistory\Event\MoneyAllHistoryEvent;
use Mattoid\MoneyHistory\Listeners\MoneyAllHistoryListener;
use Mattoid\MoneyHistory\Listeners\MoneyHistoryListener;
use Mattoid\MoneyHistory\Listeners\MoneyUpdatedHistoryListener;
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

    (new Extend\ApiSerializer(BasicUserSerializer::class))
        ->attributes(UserAttributes::class),

    (new Extend\Routes('api'))
        ->get('/users/{id}/money/history', 'user.money.history', ListUserMoneyHistoryController::class),

    (new Extend\Event())
        ->listen(MoneyUpdated::class, MoneyUpdatedHistoryListener::class)
        ->listen(MoneyHistoryEvent::class, MoneyHistoryListener::class)
        ->listen(MoneyAllHistoryEvent::class, MoneyAllHistoryListener::class),
];
