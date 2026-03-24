<?php

namespace Mattoid\MoneyHistory\Service;

use Carbon\Carbon;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Mattoid\MoneyHistory\Model\UserMoneyHistory;

class HistoryWriter
{
    public function __construct(private SettingsRepositoryInterface $settings)
    {
    }

    public function write(
        ?User $user,
        float $balanceDelta,
        string $source = '',
        string $sourceKey = '',
        array $sourceParams = [],
        ?User $actor = null,
        ?float $balanceBefore = null,
        ?float $balanceAfter = null
    ): void {
        if ($user === null || $balanceDelta === 0.0) {
            return;
        }

        $historyEntry = new UserMoneyHistory();
        $historyEntry->user_id = $user->id;
        $historyEntry->balance_delta = $balanceDelta;
        $historyEntry->source = $source;
        $historyEntry->source_key = $sourceKey;
        $historyEntry->source_params = $sourceParams;
        $historyEntry->balance_before = $balanceBefore ?? ($user->money - $balanceDelta);
        $historyEntry->balance_after = $balanceAfter ?? $user->money;
        $historyEntry->actor_id = $actor?->id ?? $user->id;
        $historyEntry->created_at = $this->currentTimestamp();
        $historyEntry->save();
    }

    public function writeMany(
        array $users,
        float $balanceDelta,
        string $source = '',
        string $sourceKey = '',
        array $sourceParams = [],
        ?User $actor = null
    ): void {
        if ($balanceDelta === 0.0) {
            return;
        }

        $createdAt = $this->currentTimestamp();
        $historyEntries = [];

        foreach ($users as $user) {
            if (! $user instanceof User) {
                continue;
            }

            $historyEntries[] = [
                'user_id' => $user->id,
                'balance_delta' => $balanceDelta,
                'balance_before' => $user->money - $balanceDelta,
                'balance_after' => $user->money,
                'source' => $source,
                'source_key' => $sourceKey,
                'source_params' => $sourceParams,
                'actor_id' => $actor?->id ?? $user->id,
                'created_at' => $createdAt,
            ];
        }

        if ($historyEntries !== []) {
            UserMoneyHistory::query()->insert($historyEntries);
        }
    }

    private function currentTimestamp(): Carbon
    {
        $storeTimezone = $this->settings->get('money-history.storeTimezone', 'Asia/Shanghai');

        return Carbon::now()->tz($storeTimezone ?: 'Asia/Shanghai');
    }
}
