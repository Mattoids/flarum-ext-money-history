<?php

/*
 * This file is part of askvortsov/flarum-moderator-warnings
 *
 *  Copyright (c) 2021 Alexander Skvortsov.
 *
 *  For detailed copyright and license information, please view the
 *  LICENSE file that was distributed with this source code.
 */

namespace Mattoid\MoneyHistory\Api\Serializer;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\BasicUserSerializer;
use Flarum\Api\Serializer\PostSerializer;
use Carbon\Carbon;
use Flarum\Post\Post;
use Flarum\Settings\SettingsRepositoryInterface;

class MoneyHistorySerializer extends AbstractSerializer
{
    protected $type = 'userMoneyHistory';
    private $storeTimezone;

    protected function getDefaultAttributes($data){
        $settings = resolve(SettingsRepositoryInterface::class);
        $storeTimezone = $settings->get('money-history.storeTimezone', 'Asia/Shanghai');
        $this->storeTimezone = !!$storeTimezone ? $storeTimezone : 'Asia/Shanghai';

        $attributes = [
            'id' => $data->id,
            'type' => $data->type,
            'money' => $data->money,
            'user_id' => $data->user_id,
            'source_desc' => $data->source_desc,
            'last_money' => $data->last_money,
            'balance_money' => $data->balance_money,
//            'create_user_id' => $data->create_user_id,
            'change_time' => Carbon::parse($data->change_time)->format('Y-m-d H:i:s'),
        ];

        return $attributes;
    }

    protected function User($moneyHistory){
        return $this->hasOne($moneyHistory, BasicUserSerializer::class);
    }

    protected function createUser($moneyHistory){
        return $this->hasOne($moneyHistory, BasicUserSerializer::class);
    }
}
