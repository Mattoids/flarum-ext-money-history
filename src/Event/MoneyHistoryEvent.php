<?php

namespace Mattoid\MoneyHistory\Event;

use Flarum\User\User;

class MoneyHistoryEvent
{

    public $user;
    public $balanceDelta;
    public $source;
    public $sourceKey;
    public $sourceDesc;
    public $actor;
    public $balanceBefore;
    public $balanceAfter;

    public function __construct(
        User $user,
        float $balanceDelta,
        string $source,
        string $sourceDesc,
        string $sourceKey,
        ?User $actor = null,
        ?float $balanceBefore = null,
        ?float $balanceAfter = null
    ) {
        $this->user = $user;
        $this->balanceDelta = $balanceDelta;
        $this->source = $source;
        $this->sourceKey = $sourceKey;
        $this->sourceDesc = $sourceDesc;
        $this->actor = $actor;
        $this->balanceBefore = $balanceBefore;
        $this->balanceAfter = $balanceAfter;
    }

}
