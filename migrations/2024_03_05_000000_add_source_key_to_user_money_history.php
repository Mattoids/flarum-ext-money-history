<?php

use Flarum\Database\Migration;

return Migration::addColumns('user_money_history', [
    'source_key' => ['string', 'length' => 255, 'nullable' => true, 'comment' => '描述对应的key，用于国际化翻译'],
]);
