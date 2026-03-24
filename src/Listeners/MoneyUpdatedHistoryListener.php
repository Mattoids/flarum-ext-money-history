<?php

namespace Mattoid\MoneyHistory\Listeners;

use AntoineFr\Money\Event\MoneyUpdated;
use Mattoid\MoneyHistory\Service\HistoryWriter;

class MoneyUpdatedHistoryListener
{
    public function __construct(private HistoryWriter $historyWriter)
    {
    }

    public function handle(MoneyUpdated $event): void
    {
        $this->historyWriter->write(
            $event->user,
            $event->balanceDelta,
            $event->source,
            $event->sourceKey,
            $event->sourceParams,
            $event->actor,
            $event->balanceBefore,
            $event->balanceAfter
        );
    }
}
