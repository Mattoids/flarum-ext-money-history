<?php

namespace Mattoid\MoneyHistory\Listeners;

use Carbon\Carbon;
use Flarum\User\User;
use Flarum\Settings\SettingsRepositoryInterface;
use Mattoid\MoneyHistory\Model\UserMoneyHistory;

abstract class BaseHistoryListener
{
    protected $source;
    protected $sourceKey;
    protected $sourceDesc;
    private $storeTimezone;

    public function storeHistoryEntry(?User $user, $money, $event = null)
    {
        $settings = resolve(SettingsRepositoryInterface::class);
        $storeTimezone = $settings->get('money-history.storeTimezone', 'Asia/Shanghai');
        $this->storeTimezone = ! ! $storeTimezone ? $storeTimezone : 'Asia/Shanghai';

        if ($money != 0) {
            $historyEntry = new UserMoneyHistory();
            $historyEntry->user_id = $user->id;
            $historyEntry->type = $money > 0 ? "C" : "D";
            $historyEntry->money = $money > 0 ? $money : -$money;
            $historyEntry->source = $this->source;
            $historyEntry->source_key = $this->sourceKey;
            $historyEntry->source_desc = $this->sourceDesc;
            $historyEntry->balance_before = isset($event->oldBalance) ? $event->oldBalance : $user->money - $money;
            $historyEntry->balance_after = isset($event->oldBalance) ? $event->oldBalance + $money : $user->money;
            $historyEntry->actor_id = $event?->actor?->id ?? $user->id;
            $historyEntry->created_at = Carbon::now()->tz($this->storeTimezone);
            $historyEntry->save();
        }
    }
}
