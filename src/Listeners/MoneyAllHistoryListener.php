<?php

namespace Mattoid\MoneyHistory\Listeners;

use Carbon\Carbon;
use Flarum\Settings\SettingsRepositoryInterface;
use Mattoid\MoneyHistory\Event\MoneyAllHistoryEvent;
use Mattoid\MoneyHistory\Model\UserMoneyHistory;

class MoneyAllHistoryListener extends BaseHistoryListener
{
    protected $source;
    protected $sourceKey;
    protected $sourceDesc;
    private $storeTimezone;

    public function handle(MoneyAllHistoryEvent $event)
    {
        $settings = resolve(SettingsRepositoryInterface::class);
        $storeTimezone = $settings->get('money-history.storeTimezone', 'Asia/Shanghai');
        $this->storeTimezone = ! ! $storeTimezone ? $storeTimezone : 'Asia/Shanghai';

        $this->source = $event->source;
        $this->sourceDesc = $event->sourceDesc;

        if ($event->money != 0) {
            $historyList = array();
            foreach ($event->list as $user) {
                $actorId = $event->actor ? $event->actor->id : $user->id;

                $historyList[] = array(
                    "user_id" => $user->id,
                    "type" => $event->money > 0 ? "C" : "D",
                    "money" => $event->money,
                    "balance_before" => $user->money - $event->money,
                    "balance_after" => $user->money,
                    "source" => $event->source,
                    "source_key" => $event->sourceKey,
                    "source_desc" => $event->sourceDesc,
                    "actor_id" => $actorId,
                    "created_at" => Carbon::now()->tz($this->storeTimezone),
                );
            }
            UserMoneyHistory::query()->insert($historyList);
        }
    }
}
