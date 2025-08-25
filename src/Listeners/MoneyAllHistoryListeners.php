<?php

namespace Mattoid\MoneyHistory\Listeners;

use Carbon\Carbon;
use Flarum\Settings\SettingsRepositoryInterface;
use Mattoid\MoneyHistory\Event\MoneyAllHistoryEvent;
use Mattoid\MoneyHistory\model\UserMoneyHistory;

class MoneyAllHistoryListeners extends HistoryListeners
{
    protected $source;
    protected $sourceKey;
    protected $sourceDesc;
    private $storeTimezone;

    public function handle(MoneyAllHistoryEvent $event) {
        $settings = resolve(SettingsRepositoryInterface::class);
        $storeTimezone = $settings->get('money-history.storeTimezone', 'Asia/Shanghai');
        $this->storeTimezone = !!$storeTimezone ? $storeTimezone : 'Asia/Shanghai';

        $this->source = $event->source;
        $this->sourceDesc = $event->sourceDesc;
        $insert = [];

        foreach ($event->list as $item) {
            $insert[] = [
                "user_id" => $item->id,
                "type" => $event->money > 0 ? "C" : "D",
                "money" => $event->money > 0 ? $event->money : -$event->money,
                "source" => $this->source,
                "source_key" => $this->sourceKey,
                "source_desc" => $this->sourceDesc,
                "balance_money" => isset($item->init_money) ? $item->init_money : $item->money - $event->money,
                "last_money" => $event->money,
                "create_user_id" => isset($item->create_user_id) ? $item->create_user_id : $item->id,
                "change_time" => Carbon::now()->tz($this->storeTimezone)
            ];
        }

        UserMoneyHistory::query()->insert($insert);
    }
}
