<?php

use Illuminate\Database\Schema\Blueprint;

use Flarum\Database\Migration;

return Migration::createTable(
    'user_money_history',
    function (Blueprint $table) {
        $table->increments('id');

        $table->integer('user_id')->index();
        $table->char("type", 1)->comment("入账方式 C-入账 D-出账");
        $table->double('money')->default(0)->comment("消费金额");
        $table->varchar("source")->comment("资金用途")->index();
        $table->varchar('source_desc') -> comment("资金用途描述");
        $table->double('balance_money')->comment("变更前余额");
        $table->double('last_money')->comment("变更后余额");
        $table->integer('create_user_id')->comment("触发人员ID")->index();
        $table->dateTime('change_time') -> comment("资金变更时间")->index();
    }
);
