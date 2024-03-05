<?php

namespace Mattoid\MoneyHistory\Listeners;

use Mattoid\MoneyHistory\Event\MoneyHistoryEvent;

class MoneyHistoryListeners extends HistoryListeners
{
    protected $source = "";
    protected $sourceKey = "";
    protected $sourceDesc = "";

    public function handle(MoneyHistoryEvent $event) {
        $this->source = $event->source;
        $this->sourceKey = $event->sourceKey;
        $this->sourceDesc = $event->sourceDesc;

        $this->exec($event->user, $event->money);
    }
}
