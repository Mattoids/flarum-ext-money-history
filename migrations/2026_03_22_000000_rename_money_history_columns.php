<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;

return function (Builder $schema) {
    if (! $schema->hasTable('user_money_history')) {
        return;
    }

    if ($schema->hasColumn('user_money_history', 'create_user_id') && ! $schema->hasColumn('user_money_history', 'actor_id')) {
        $schema->table('user_money_history', function (Blueprint $table) {
            $table->renameColumn('create_user_id', 'actor_id');
        });
    }

    if ($schema->hasColumn('user_money_history', 'balance_money') && ! $schema->hasColumn('user_money_history', 'balance_before')) {
        $schema->table('user_money_history', function (Blueprint $table) {
            $table->renameColumn('balance_money', 'balance_before');
        });
    }

    if ($schema->hasColumn('user_money_history', 'last_money') && ! $schema->hasColumn('user_money_history', 'balance_after')) {
        $schema->table('user_money_history', function (Blueprint $table) {
            $table->renameColumn('last_money', 'balance_after');
        });
    }

    if ($schema->hasColumn('user_money_history', 'change_time') && ! $schema->hasColumn('user_money_history', 'created_at')) {
        $schema->table('user_money_history', function (Blueprint $table) {
            $table->renameColumn('change_time', 'created_at');
        });
    }

    if ($schema->hasColumn('user_money_history', 'money') && ! $schema->hasColumn('user_money_history', 'balance_delta')) {
        $schema->table('user_money_history', function (Blueprint $table) {
            $table->renameColumn('money', 'balance_delta');
        });
    }

    if ($schema->hasColumn('user_money_history', 'balance_delta') && $schema->hasColumn('user_money_history', 'type')) {
        DB::table('user_money_history')
            ->where('type', 'D')
            ->update(['balance_delta' => DB::raw('-ABS(balance_delta)')]);

        DB::table('user_money_history')
            ->where('type', 'C')
            ->update(['balance_delta' => DB::raw('ABS(balance_delta)')]);

        $schema->table('user_money_history', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    if (! $schema->hasColumn('user_money_history', 'source_params')) {
        $schema->table('user_money_history', function (Blueprint $table) {
            $table->text('source_params')->nullable()->comment('资金用途描述参数');
        });
    }

    if ($schema->hasColumn('user_money_history', 'source_desc')) {
        $schema->table('user_money_history', function (Blueprint $table) {
            $table->dropColumn('source_desc');
        });
    }
};
