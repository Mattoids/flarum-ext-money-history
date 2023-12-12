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
use Flarum\Api\Serializer\PostSerializer;
use Flarum\Post\Post;
use Flarum\Settings\SettingsRepositoryInterface;
use Mattoid\MoneyHistory\model\UserMoneyHistory;

class MoneyHistorySerializer extends AbstractSerializer
{
    protected $type = 'checkin.history';
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
            'type' => $money->type === 'C' ? '奖励' : '扣除',
            'money' => $money->money,
            'source_desc' => $money->source_desc,
            'change_time' => $money->change_time,
        ];
    }

    protected function format($text)
    {
        return UserMoneyHistory::getFormatter()->render($text, new Post());
    }

    protected function post($history)
    {
        return $this->hasOne($history, PostSerializer::class);
    }
}
