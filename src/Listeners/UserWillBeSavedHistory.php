<?php

namespace Mattoid\MoneyHistory\Listeners;

use Illuminate\Support\Arr;
use Flarum\User\Event\Saving;
use Flarum\Notification\NotificationSyncer;
use Flarum\Settings\SettingsRepositoryInterface;

class UserWillBeSavedHistory extends HistoryListeners
{
    protected $source = "USERWILLBESAVED";
    protected $sourceDesc = "";

    private $settings;
    private $autoremove;

    public function __construct(NotificationSyncer $notifications, SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
        $this->notifications = $notifications;

        $this->autoremove = (int)$this->settings->get('antoinefr-money.autoremove', 1);
    }

    public function handle(Saving $event) {
        $attributes = Arr::get($event->data, 'attributes', []);

        if (array_key_exists('money', $attributes)) {
            $this->exec($event->user, (float)$attributes['money']);
        }
    }
}
