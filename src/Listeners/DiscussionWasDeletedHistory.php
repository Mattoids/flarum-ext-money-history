<?php

namespace Mattoid\MoneyHistory\Listeners;

use AntoineFr\Money\Listeners\AutoRemoveEnum;
use Flarum\Notification\NotificationSyncer;
use Flarum\Discussion\Event\Deleted as DiscussionDeleted;
use Flarum\Settings\SettingsRepositoryInterface;

class DiscussionWasDeletedHistory extends HistoryListeners
{
    protected $source = "DISCUSSIONWASDELETED";
    protected $sourceDesc = "";

    private $settings;
    private $autoremove;

    public function __construct(NotificationSyncer $notifications, SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $this->notifications = $notifications;

        $this->autoremove = (int)$this->settings->get('antoinefr-money.autoremove', 1);
    }

    public function handle(DiscussionDeleted $event) {
        if ($this->autoremove == AutoRemoveEnum::DELETED) {
            $money = (float)$this->settings->get('antoinefr-money.moneyfordiscussion', 0);
            $this->exec($event->discussion->user, -$money);
        }
    }
}
