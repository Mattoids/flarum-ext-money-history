<?php

namespace Mattoid\MoneyHistory\Provider;

use AntoineFr\Money\Contract\BalanceHistoryRecorder;
use Illuminate\Support\ServiceProvider;
use Mattoid\MoneyHistory\MoneyHistoryRecorder;

class MoneyHistoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BalanceHistoryRecorder::class, MoneyHistoryRecorder::class);
    }
}
