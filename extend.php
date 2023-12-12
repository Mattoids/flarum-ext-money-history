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
use Flarum\Post\Event\Posted;
use Flarum\Post\Event\Restored as PostRestored;
use Flarum\Post\Event\Hidden as PostHidden;
use Flarum\Post\Event\Deleted as PostDeleted;
use Flarum\Discussion\Event\Started;
use Flarum\Discussion\Event\Restored as DiscussionRestored;
use Flarum\Discussion\Event\Hidden as DiscussionHidden;
use Flarum\Discussion\Event\Deleted as DiscussionDeleted;
use Flarum\User\Event\Saving;
use Flarum\Likes\Event\PostWasLiked;
use Flarum\Likes\Event\PostWasUnliked;
use Mattoid\MoneyHistory\Listeners\CheckinSavedHistory;
use Mattoid\MoneyHistory\Listeners\DiscussionWasDeletedHistory;
use Mattoid\MoneyHistory\Listeners\DiscussionWasHiddenHistory;
use Mattoid\MoneyHistory\Listeners\DiscussionWasRestoredHistory;
use Mattoid\MoneyHistory\Listeners\DiscussionWasStartedHistory;
use Mattoid\MoneyHistory\Listeners\MoneyHistoryListeners;
use Mattoid\MoneyHistory\Listeners\PostWasDeletedHistory;
use Mattoid\MoneyHistory\Listeners\PostWasHiddenHistory;
use Mattoid\MoneyHistory\Listeners\PostWasLikedHistory;
use Mattoid\MoneyHistory\Listeners\PostWasPostedHistory;
use Mattoid\MoneyHistory\Listeners\PostWasRestoredHistory;
use Mattoid\MoneyHistory\Listeners\PostWasUnlikedHistory;
use Mattoid\MoneyHistory\Listeners\UserWillBeSavedHistory;
use Mattoid\MoneyHistory\Middleware\HistoryMiddleware;
use Mattoid\MoneyHistory\Event\MoneyHistoryEvent;

$extend =  [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Middleware("api"))->add(HistoryMiddleware::class),

    (new Extend\Event())
        ->listen(Posted::class, PostWasPostedHistory::class)
        ->listen(PostRestored::class, PostWasRestoredHistory::class)
        ->listen(PostHidden::class, PostWasHiddenHistory::class)
        ->listen(PostDeleted::class, PostWasDeletedHistory::class)
        ->listen(Started::class, DiscussionWasStartedHistory::class)
        ->listen(DiscussionRestored::class, DiscussionWasRestoredHistory::class)
        ->listen(DiscussionHidden::class, DiscussionWasHiddenHistory::class)
        ->listen(DiscussionDeleted::class, DiscussionWasDeletedHistory::class)
        ->listen(Saving::class, UserWillBeSavedHistory::class)
        ->listen(MoneyHistoryEvent::class, MoneyHistoryListeners::class),
];

if (class_exists('Flarum\Likes\Event\PostWasLiked')) {
    $extend[] =
        (new Extend\Event())
            ->listen(PostWasLiked::class, PostWasLikedHistory::class)
            ->listen(PostWasUnliked::class, PostWasUnlikedHistory::class)
    ;
}

if (class_exists('Ziven\checkin\Event\checkinUpdated')) {
    $extend[] =
        (new Extend\Event())
            ->listen(\Ziven\checkin\Event\checkinUpdated::class, CheckinSavedHistory::class);
}

return $extend;
