<?php

namespace Mattoid\MoneyHistory\Event;

class MoneyAllHistoryEvent
{

    public $list;
    public $money;
    public $source;
    public $sourceDesc;

    public function __construct($list = array(), $money = 0, $source = "", $sourceDesc = "")
    {
        $this->list = $list;
        $this->money = $money;
        $this->source = $source;
        $this->sourceDesc = $sourceDesc;
    }

}
