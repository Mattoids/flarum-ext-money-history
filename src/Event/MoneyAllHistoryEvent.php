<?php

namespace Mattoid\MoneyHistory\Event;

class MoneyAllHistoryEvent
{

    public $list;
    public $money;
    public $source;
    public $sourceKey;
    public $sourceDesc;

    public function __construct($list = array(), $money = 0, $source = "", $sourceDesc = "", $sourceKey = "")
    {
        $this->list = $list;
        $this->money = $money;
        $this->source = $source;
        $this->sourceKey = $sourceKey;
        $this->sourceDesc = $sourceDesc;
    }

}
