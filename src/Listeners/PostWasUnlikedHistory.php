<?php

namespace Mattoid\MoneyHistory\Listeners;

use Flarum\Likes\Event\PostWasUnliked;
use Flarum\Notification\NotificationSyncer;
use Flarum\Settings\SettingsRepositoryInterface;

class PostWasUnlikedHistory extends HistoryListeners
{
    protected $source = "POSTWASUNLIKED";
    protected $sourceDesc = "";

    private $settings;
    private $autoremove;

    public function __construct(NotificationSyncer $notifications, SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $this->notifications = $notifications;

        $this->autoremove = (int)$this->settings->get('antoinefr-money.autoremove', 1);
    }

    public function handle(PostWasUnliked $event) {
        $money = (float)$this->settings->get('antoinefr-money.moneyforlike', 0);
        $this->exec($event->post->user, -$money);
    }
}
