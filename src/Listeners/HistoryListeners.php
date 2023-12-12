<?php

namespace Mattoid\MoneyHistory\Listeners;

use Flarum\User\User;
use Mattoid\MoneyHistory\model\UserMoneyHistory;

abstract class HistoryListeners
{

    protected $source;
    protected $sourceDesc;

    public function exec(?User $user, $money) {

        if ($money > 0 || $money < 0) {
            $userMoneyHistory = new UserMoneyHistory();
            $userMoneyHistory->user_id = $user->id;
            $userMoneyHistory->type = $money > 0 ? "C" : "D";
            $userMoneyHistory->money = $money > 0 ? $money : -$money;
            $userMoneyHistory->source = $this->source;
            $userMoneyHistory->source_desc = $this->sourceDesc;
            $userMoneyHistory->balance_money = isset($user->init_money) ? $user->init_money : $user->money - $money;
            $userMoneyHistory->last_money = $user->money;
            $userMoneyHistory->create_user_id = isset($user->create_user_id) ? $user->create_user_id : $user->id;
            $userMoneyHistory->change_time = Date("Y-m-d H:i:s");
            $userMoneyHistory->save();
        }
    }

}
