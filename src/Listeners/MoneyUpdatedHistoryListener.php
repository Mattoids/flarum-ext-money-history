<?php

namespace Mattoid\MoneyHistory\Listeners;

use AntoineFr\Money\Event\MoneyUpdated;

class MoneyUpdatedHistoryListener extends BaseHistoryListener
{
    public function handle(MoneyUpdated $event): void
    {
        $this->source = $event->source;
        $this->sourceKey = $event->sourceKey;
        $this->sourceParams = $event->sourceParams;

        $this->storeHistoryEntry($event->user, $event->balanceDelta, $event);
    }
}
