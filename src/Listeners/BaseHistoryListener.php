<?php

namespace Mattoid\MoneyHistory\Listeners;

use Carbon\Carbon;
use Flarum\User\User;
use Flarum\Settings\SettingsRepositoryInterface;
use Mattoid\MoneyHistory\model\UserMoneyHistory;

abstract class BaseHistoryListener
{
    protected $source;
    protected $sourceKey;
    protected $sourceDesc;
    private $storeTimezone;

    public function exec(?User $user, $money, $event = null)
    {
        $settings = resolve(SettingsRepositoryInterface::class);
        $storeTimezone = $settings->get('money-history.storeTimezone', 'Asia/Shanghai');
        $this->storeTimezone = !!$storeTimezone ? $storeTimezone : 'Asia/Shanghai';

        if ($money != 0) {
            $userMoneyHistory = new UserMoneyHistory();
            $userMoneyHistory->user_id = $user->id;
            $userMoneyHistory->type = $money > 0 ? "C" : "D";
            $userMoneyHistory->money = $money > 0 ? $money : -$money;
            $userMoneyHistory->source = $this->source;
            $userMoneyHistory->source_key = $this->sourceKey;
            $userMoneyHistory->source_desc = $this->sourceDesc;
            $userMoneyHistory->balance_before = isset($event->oldBalance) ? $event->oldBalance : $user->money - $money;
            $userMoneyHistory->balance_after = isset($event->oldBalance) ? $event->oldBalance + $money : $user->money;
            $userMoneyHistory->actor_id = $event?->actor?->id ?? $user->id;
            $userMoneyHistory->created_at = Carbon::now()->tz($this->storeTimezone);
            $userMoneyHistory->save();
        }
    }
}
