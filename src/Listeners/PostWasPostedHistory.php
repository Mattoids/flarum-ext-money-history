<?php

namespace Mattoid\MoneyHistory\Listeners;

use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Event\Posted;

class PostWasPostedHistory
{
    private $sourceDesc = "发帖奖励";

    public function __construct(NotificationSyncer $notifications)
    {
        $this->notifications = $notifications;
    }

    public function handle(Posted $event) {

    }
}
