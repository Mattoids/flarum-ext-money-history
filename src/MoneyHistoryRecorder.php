<?php

namespace Mattoid\MoneyHistory;

use AntoineFr\Money\Contract\BalanceHistoryRecorder as BalanceHistoryRecorder;
use Flarum\User\User;
use Mattoid\MoneyHistory\Service\HistoryWriter;

class MoneyHistoryRecorder implements BalanceHistoryRecorder
{
    public function __construct(private HistoryWriter $historyWriter)
    {
    }

    public function record(
        ?User $user,
        float $balanceDelta,
        string $source = '',
        string $sourceKey = '',
        array $sourceParams = [],
        ?User $actor = null,
        ?float $balanceBefore = null,
        ?float $balanceAfter = null
    ): void {
        $this->historyWriter->write(
            $user,
            $balanceDelta,
            $source,
            $sourceKey,
            $sourceParams,
            $actor,
            $balanceBefore,
            $balanceAfter
        );
    }
}
