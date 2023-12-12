<?php

namespace Mattoid\MoneyHistory\Event;

use Flarum\User\User;

class MoneyHistoryEvent
{

    public $user;
    public $money;
    public $source;
    public $sourceDesc;

    public function __construct(User $user = null, $money = 0, $source = "", $sourceDesc = "")
    {
        $this->user = $user;
        $this->money = $money;
        $this->source = $source;
        $this->sourceDesc = $sourceDesc;
    }

}
