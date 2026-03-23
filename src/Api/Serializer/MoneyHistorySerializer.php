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

use Carbon\Carbon;
use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\BasicUserSerializer;
use Flarum\Settings\SettingsRepositoryInterface;

class MoneyHistorySerializer extends AbstractSerializer
{
    protected $type = 'userMoneyHistory';
    private $storeTimezone;

    protected function getDefaultAttributes($data)
    {
        $settings = resolve(SettingsRepositoryInterface::class);
        $storeTimezone = $settings->get('money-history.storeTimezone', 'Asia/Shanghai');
        $this->storeTimezone = ! ! $storeTimezone ? $storeTimezone : 'Asia/Shanghai';

        $attributes = [
            'id' => $data->id,
            'balance_delta' => $data->balance_delta,
            'user_id' => $data->user_id,
            'source_desc' => $data->source_desc,
            'balance_after' => $data->balance_after,
            'balance_before' => $data->balance_before,
            // 'actor_id' => $data->actor_id,
            'created_at' => Carbon::parse($data->created_at)->format('Y-m-d H:i:s'),
        ];

        return $attributes;
    }

    protected function user($moneyHistory)
    {
        return $this->hasOne($moneyHistory, BasicUserSerializer::class);
    }

    protected function actor($moneyHistory)
    {
        return $this->hasOne($moneyHistory, BasicUserSerializer::class);
    }
}
