<?php

namespace Mattoid\MoneyHistory\Listeners;

use Carbon\Carbon;
use Flarum\User\User;
use Flarum\Settings\SettingsRepositoryInterface;
use Mattoid\MoneyHistory\model\UserMoneyHistory;

abstract class HistoryListeners
{

    protected $source;
    protected $sourceKey;
    protected $sourceDesc;
    private $storeTimezone;

    public function exec(?User $user, $money) {
        $settings = resolve(SettingsRepositoryInterface::class);
        $storeTimezone = $settings->get('money-history.storeTimezone', 'Asia/Shanghai');
        $this->storeTimezone = !!$storeTimezone ? $storeTimezone : 'Asia/Shanghai';

        if ($money > 0 || $money < 0) {
            $userMoneyHistory = new UserMoneyHistory();
            $userMoneyHistory->user_id = $user->id;
            $userMoneyHistory->type = $money > 0 ? "C" : "D";
            $userMoneyHistory->money = $money > 0 ? $money : -$money;
            $userMoneyHistory->source = $this->source;
            $userMoneyHistory->source_key = $this->sourceKey;
            $userMoneyHistory->source_desc = $this->sourceDesc;
            $userMoneyHistory->balance_money = isset($user->init_money) ? $user->init_money : $user->money - $money;
            $userMoneyHistory->last_money = $user->money;
            $userMoneyHistory->create_user_id = isset($user->create_user_id) ? $user->create_user_id : $user->id;
            $userMoneyHistory->change_time = Carbon::now()->tz($this->storeTimezone);
            $userMoneyHistory->save();
        }
    }

}
