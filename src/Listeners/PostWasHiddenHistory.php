<?php

namespace Mattoid\MoneyHistory\Listeners;

use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Event\Hidden as PostHidden;
class PostWasHiddenHistory
{
    private $sourceDesc = "隐藏帖子";

    public function __construct(NotificationSyncer $notifications)
    {
        $this->notifications = $notifications;
    }

    public function handle(PostHidden $event) {

    }
}
