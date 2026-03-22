<?php

namespace Mattoid\MoneyHistory\Event;

use Flarum\User\User;

class MoneyHistoryEvent
{

    public $user;
    public $money;
    public $source;
    public $sourceKey;
    public $sourceDesc;
    public $actor;
    public $oldBalance;

    public function __construct(User $user, float $money, string $source, string $sourceDesc, string $sourceKey, ?User $actor = null, ?float $oldBalance = null)
    {
        $this->user = $user;
        $this->money = $money;
        $this->source = $source;
        $this->sourceKey = $sourceKey;
        $this->sourceDesc = $sourceDesc;
        $this->actor = $actor;
        $this->oldBalance = $oldBalance;
    }

}
