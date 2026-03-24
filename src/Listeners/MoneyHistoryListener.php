<?php

namespace Mattoid\MoneyHistory\Listeners;

use Mattoid\MoneyHistory\Event\MoneyHistoryEvent;
use Mattoid\MoneyHistory\Service\HistoryWriter;

class MoneyHistoryListener
{
    public function __construct(private HistoryWriter $historyWriter)
    {
    }

    public function handle(MoneyHistoryEvent $event): void
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
