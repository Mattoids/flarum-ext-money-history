<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        if (! $schema->hasTable('user_money_history')) {
            return;
        }

        if (! $schema->hasColumn('user_money_history', 'balance_delta') || ! $schema->hasColumn('user_money_history', 'type')) {
            return;
        }

        $connection = $schema->getConnection();
        $legacyRows = $connection->table('user_money_history')
            ->select(['id', 'type', 'balance_delta'])
            ->get();

        foreach ($legacyRows as $legacyRow) {
            $normalizedDelta = abs((float) $legacyRow->balance_delta);

            if ($legacyRow->type === 'D') {
                $normalizedDelta *= -1;
            }

            $connection->table('user_money_history')
                ->where('id', $legacyRow->id)
                ->update(['balance_delta' => $normalizedDelta]);
        }

        $schema->table('user_money_history', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    },
    'down' => function (Builder $schema) {
        // Not doing anything but `down` has to be defined
    },
];
