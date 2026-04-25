<?php

use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if (! $schema->hasTable('user_money_history') || ! $schema->hasColumn('user_money_history', 'source')) {
            return;
        }

        $connection = $schema->getConnection();
        $sourceMap = [
            'POSTWASPOSTED' => 'POST_POSTED',
            'POSTWASRESTORED' => 'POST_RESTORED',
            'POSTWASHIDDEN' => 'POST_HIDDEN',
            'POSTWASDELETED' => 'POST_DELETED',
            'DISCUSSIONWASSTARTED' => 'DISCUSSION_STARTED',
            'DISCUSSIONWASRESTORED' => 'DISCUSSION_RESTORED',
            'DISCUSSIONWASHIDDEN' => 'DISCUSSION_HIDDEN',
            'DISCUSSIONWASDELETED' => 'DISCUSSION_DELETED',
            'USERWILLBESAVED' => 'MANUAL_ADJUSTMENT',
            'POSTWASLIKED' => 'POST_LIKED',
            'POSTWASUNLIKED' => 'POST_UNLIKED',
            'MONEYREWARDS' => 'POST_REWARD',
            'MONEYTOALL' => 'MONEY_TO_ALL',
            'CHECKINSAVED' => 'DAILY_CHECKIN_REWARD',
            'STOREBUYGOODS' => 'STORE_BUY_GOODS',
            'STOREBUYGOODSFAIL' => 'STORE_BUY_GOODS_FAIL',
            'AUTODEDUCTION' => 'STORE_AUTO_DEDUCTION',
            'CONFIRMINVITE' => 'STORE_CONFIRM_INVITE',
        ];

        foreach ($sourceMap as $legacySource => $normalizedSource) {
            $connection->table('user_money_history')
                ->where('source', $legacySource)
                ->update(['source' => $normalizedSource]);
        }
    },
    'down' => function (Builder $schema) {
        // Not doing anything but `down` has to be defined
    },
];
