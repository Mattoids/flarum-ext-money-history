<?php

namespace Mattoid\MoneyHistory\Listeners;

use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Event\Restored as PostRestored;

class PostWasLikedHistory
{
    private $sourceDesc = "帖子恢复";

    public function __construct(NotificationSyncer $notifications)
    {
        $this->notifications = $notifications;
    }

    public function handle(PostRestored $event) {

    }
}
