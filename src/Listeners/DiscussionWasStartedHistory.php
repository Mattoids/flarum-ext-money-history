<?php

namespace Mattoid\MoneyHistory\Listeners;

use Flarum\Notification\NotificationSyncer;
use Flarum\Discussion\Event\Started;
use Flarum\Settings\SettingsRepositoryInterface;

class DiscussionWasStartedHistory extends HistoryListeners
{
    protected $source = "DISCUSSIONWASSTARTED";
    protected $sourceDesc = "发帖奖励";

    private $settings;
    private $autoremove;

    public function __construct(NotificationSyncer $notifications, SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $this->notifications = $notifications;

        $this->autoremove = (int)$this->settings->get('antoinefr-money.autoremove', 1);
    }

    public function handle(Started $event) {
        $money = (float)$this->settings->get('antoinefr-money.moneyfordiscussion', 0);
        $this->exec($event->actor, $money);
    }
}
