<?php

namespace Mattoid\MoneyHistory\Listeners;

use Carbon\Carbon;
use Flarum\Settings\SettingsRepositoryInterface;
use Mattoid\MoneyHistory\Event\MoneyAllHistoryEvent;
use Mattoid\MoneyHistory\model\UserMoneyHistory;

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
        $this->storeTimezone = !!$storeTimezone ? $storeTimezone : 'Asia/Shanghai';

        $this->source = $event->source;
        $this->sourceDesc = $event->sourceDesc;

        if ($event->money != 0) {
            $insertList = array();
            foreach ($event->list as $item) {
                $actorId = $event->actor ? $event->actor->id : $item->id;

                $insertList[] = array(
                    "user_id" => $item->id,
                    "type" => $event->money > 0 ? "C" : "D",
                    "money" => $event->money,
                    "balance_before" => $item->money - $event->money,
                    "balance_after" => $item->money,
                    "source" => $event->source,
                    "source_key" => $event->sourceKey,
                    "source_desc" => $event->sourceDesc,
                    "actor_id" => $actorId,
                    "created_at" => Carbon::now()->tz($this->storeTimezone),
                );
            }
            UserMoneyHistory::query()->insert($insertList);
        }
    }
}
