<?php

namespace Mattoid\MoneyHistory\Listeners;

use AntoineFr\Money\Listeners\AutoRemoveEnum;
use Flarum\Notification\NotificationSyncer;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Discussion\Event\Hidden as DiscussionHidden;

class DiscussionWasHiddenHistory extends HistoryListeners
{

    protected $source = "DISCUSSIONWASHIDDEN";
    protected $sourceDesc = "";

    private $settings;
    private $autoremove;

    public function __construct(NotificationSyncer $notifications, SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $this->notifications = $notifications;

        $this->autoremove = (int)$this->settings->get('antoinefr-money.autoremove', 1);
    }
    public function handle(DiscussionHidden $event) {
        if ($this->autoremove == AutoRemoveEnum::HIDDEN) {
            $money = (float)$this->settings->get('antoinefr-money.moneyfordiscussion', 0);
            $this->exec($event->discussion->user, -$money);
        }
    }
}
