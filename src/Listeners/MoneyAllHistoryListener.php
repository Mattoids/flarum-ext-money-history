<?php

namespace Mattoid\MoneyHistory\Listeners;

use Mattoid\MoneyHistory\Event\MoneyAllHistoryEvent;
use Mattoid\MoneyHistory\Service\HistoryWriter;

class MoneyAllHistoryListener
{
    public function __construct(private HistoryWriter $historyWriter)
    {
    }

    public function handle(MoneyAllHistoryEvent $event): void
    {
        $this->historyWriter->writeMany(
            $event->list,
            $event->balanceDelta,
            $event->source,
            $event->sourceKey,
            $event->sourceParams,
            $event->actor
        );
    }
}
