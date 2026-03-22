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
        $insert = [];

        if ($event->money != 0) {
            $insertList = array();
            foreach ($event->list as $item) {
                $create_user_id = $event->actor ? $event->actor->id : (isset($item->create_user_id) ? $item->create_user_id : $item->id);

                $insertList[] = array(
                    "user_id" => $item->id,
                    "type" => $event->money > 0 ? "C" : "D",
                    "money" => $event->money,
                    "balance_money" => $item->money - $event->money,
                    "last_money" => $item->money,
                    "source" => $event->source,
                    "source_key" => $event->sourceKey,
                    "source_desc" => $event->sourceDesc,
                    "create_user_id" => $create_user_id,
                    "change_time" => Carbon::now()->tz($this->storeTimezone),
                );
            }
            UserMoneyHistory::query()->insert($insertList);
        }
    }
}
