<?php

use Illuminate\Database\Schema\Blueprint;

use Flarum\Database\Migration;

return Migration::createTable(
    'user_operate_log',
    function (Blueprint $table) {
        $table->increments('id');
        $table->integer("user_id")->comment("操作人ID")->index();
        $table->string("uri")->comment("接口地址")->index();
        $table->string("method")->comment("请求方法");
        $table->string("ip")->comment("请求IP");
        $table->string("request", 2048)->comment("请求入参");
        $table->string("response", 2048)->comment("返回数据");
        $table->dateTime('created_at') -> comment("操作时间")->index();
    }
);

