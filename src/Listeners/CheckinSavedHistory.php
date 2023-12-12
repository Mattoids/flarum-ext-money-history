<?php

namespace Mattoid\MoneyHistory\Listeners;

use Flarum\Notification\NotificationSyncer;
use Flarum\Settings\SettingsRepositoryInterface;
use Ziven\checkin\Event\checkinUpdated;

class CheckinSavedHistory extends HistoryListeners
{
    protected $source = "CHECKINSAVED";
    protected $sourceDesc = "签到奖励";

    private $settings;

    public function __construct(NotificationSyncer $notifications, SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $this->notifications = $notifications;
    }

    public function handle(checkinUpdated $checkin) {
        $checkinRewardMoney = (float)$this->settings->get('ziven-forum-checkin.checkinRewardMoney', 0);
        $this->exec($checkin->user, $checkinRewardMoney);
    }
}
