<?php

namespace Mattoid\MoneyHistory\Event;

use Flarum\User\User;

class MoneyAllHistoryEvent
{

    public $list;
    public $balanceDelta;
    public $source;
    public $sourceKey;
    public $sourceDesc;
    public $actor;
    public $balanceBefore;
    public $balanceAfter;

    public function __construct(
        array $list,
        float $balanceDelta,
        string $source,
        string $sourceDesc,
        string $sourceKey,
        ?User $actor = null,
        ?float $balanceBefore = null,
        ?float $balanceAfter = null
    ) {
        $this->list = $list;
        $this->balanceDelta = $balanceDelta;
        $this->source = $source;
        $this->sourceKey = $sourceKey;
        $this->sourceDesc = $sourceDesc;
        $this->actor = $actor;
        $this->balanceBefore = $balanceBefore;
        $this->balanceAfter = $balanceAfter;
    }

}
