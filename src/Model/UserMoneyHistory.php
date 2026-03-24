<?php

namespace Mattoid\MoneyHistory\Model;

use Flarum\Database\AbstractModel;
use Flarum\User\User;

class UserMoneyHistory extends AbstractModel
{
    protected $table = "user_money_history";
    protected $casts = [
        'source_params' => 'array',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function actor()
    {
        return $this->hasOne(User::class, 'id', 'actor_id');
    }
}
