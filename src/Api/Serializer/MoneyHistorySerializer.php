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
use Flarum\Post\Post;
use Flarum\Settings\SettingsRepositoryInterface;
use Mattoid\MoneyHistory\model\UserMoneyHistory;

class MoneyHistorySerializer extends AbstractSerializer
{
    protected $type = 'user.money.history';
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAttributes($money)
    {
        return [
            'id' => $money->id,
            'user_id' => $money->user_id,
            'type' => $money->type,
            'money' => $money->money,
            'source_desc' => $money->source_desc,
            'change_time' => $money->change_time,
            'create_user' => $money->createUser(),
            'create_user_id' => $money->create_user_id,
        ];
    }

    protected function user($transferHistory){
        return $this->hasOne($transferHistory, BasicUserSerializer::class);
    }

    protected function createUser($transferHistory){
        return $this->hasOne($transferHistory, BasicUserSerializer::class);
    }
}
