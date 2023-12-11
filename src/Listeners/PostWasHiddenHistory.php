<?php

namespace Mattoid\MoneyHistory\Listeners;

use AntoineFr\Money\Listeners\AutoRemoveEnum;
use Flarum\Notification\NotificationSyncer;
use Flarum\Post\Event\Hidden as PostHidden;
use Flarum\Settings\SettingsRepositoryInterface;

class PostWasHiddenHistory extends HistoryListeners
{
    protected $source = "POSTWASHIDDEN";
    protected $sourceDesc = "";

    private $settings;
    private $autoremove;

    public function __construct(NotificationSyncer $notifications, SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $this->notifications = $notifications;

        $this->autoremove = (int)$this->settings->get('antoinefr-money.autoremove', 1);
    }

    public function handle(PostHidden $event) {
        if ($this->autoremove == AutoRemoveEnum::HIDDEN) {
            $minimumLength = (int)$this->settings->get('antoinefr-money.postminimumlength', 0);

            if (strlen($event->post->content) >= $minimumLength) {
                $money = (float)$this->settings->get('antoinefr-money.moneyforpost', 0);
                $this->exec($event->post->user, -$money);
            }
        }
    }
}
