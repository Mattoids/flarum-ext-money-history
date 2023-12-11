<?php

namespace Mattoid\MoneyHistory\Listeners;

use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Event\Posted;

class PostWasPostedHistory extends HistoryListeners
{
    protected $source = "POSTWASPOSTED";
    protected $sourceDesc = "回帖奖励";

    private $settings;
    private $autoremove;

    public function __construct(NotificationSyncer $notifications, SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $this->notifications = $notifications;

        $this->autoremove = (int)$this->settings->get('antoinefr-money.autoremove', 1);
    }

    public function handle(Posted $event) {
        if ($event->post['number'] > 1) {
            $minimumLength = (int)$this->settings->get('antoinefr-money.postminimumlength', 0);

            if (strlen($event->post->content) >= $minimumLength) {
                $money = (float)$this->settings->get('antoinefr-money.moneyforpost', 0);

                $this->exec($event->actor, $money);
            }
        }
    }
}
