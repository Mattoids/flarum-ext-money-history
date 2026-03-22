<?php

use Flarum\Database\Migration;

return Migration::renameColumns('user_money_history', [
    'create_user_id' => 'actor_id',
    'balance_money' => 'balance_before',
    'last_money' => 'balance_after',
    'change_time' => 'created_at'
]);
