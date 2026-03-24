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

class MoneyHistorySerializer extends AbstractSerializer
{
    protected $type = 'userMoneyHistory';

    protected function getDefaultAttributes($data)
    {
        return [
            'id' => $data->id,
            'balance_delta' => $data->balance_delta,
            'user_id' => $data->user_id,
            'source' => $data->source,
            'source_key' => $data->source_key,
            'source_params' => $data->source_params,
            'balance_after' => $data->balance_after,
            'balance_before' => $data->balance_before,
            'created_at' => Carbon::parse($data->created_at)->format('Y-m-d H:i:s'),
        ];
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
