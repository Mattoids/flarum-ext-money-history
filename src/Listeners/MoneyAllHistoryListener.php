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
    private $storeTimezone;

    public function handle(MoneyAllHistoryEvent $event)
    {
        $settings = resolve(SettingsRepositoryInterface::class);
        $storeTimezone = $settings->get('money-history.storeTimezone', 'Asia/Shanghai');
        $this->storeTimezone = ! ! $storeTimezone ? $storeTimezone : 'Asia/Shanghai';

        $this->source = $event->source;
        $this->sourceKey = $event->sourceKey;
        $this->sourceParams = $event->sourceParams;

        if ($event->balanceDelta != 0) {
            $historyList = array();
            foreach ($event->list as $user) {
                $actorId = $event->actor ? $event->actor->id : $user->id;

                $historyList[] = array(
                    "user_id" => $user->id,
                    "balance_delta" => $event->balanceDelta,
                    "balance_before" => $user->money - $event->balanceDelta,
                    "balance_after" => $user->money,
                    "source" => $event->source,
                    "source_key" => $event->sourceKey,
                    "source_params" => $event->sourceParams,
                    "actor_id" => $actorId,
                    "created_at" => Carbon::now()->tz($this->storeTimezone),
                );
            }
            UserMoneyHistory::query()->insert($historyList);
        }
    }
}
