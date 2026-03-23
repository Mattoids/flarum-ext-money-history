<?php

namespace Mattoid\MoneyHistory\Listeners;

use Carbon\Carbon;
use Flarum\User\User;
use Flarum\Settings\SettingsRepositoryInterface;
use Mattoid\MoneyHistory\Model\UserMoneyHistory;

abstract class BaseHistoryListener
{
    protected $source;
    protected $sourceKey;
    protected $sourceDesc;
    private $storeTimezone;

    public function storeHistoryEntry(?User $user, float $balanceDelta, $event = null): void
    {
        if ($user === null || $balanceDelta == 0.0) {
            return;
        }

        $settings = resolve(SettingsRepositoryInterface::class);
        $storeTimezone = $settings->get('money-history.storeTimezone', 'Asia/Shanghai');
        $this->storeTimezone = ! ! $storeTimezone ? $storeTimezone : 'Asia/Shanghai';

        $historyEntry = new UserMoneyHistory();
        $historyEntry->user_id = $user->id;
        $historyEntry->balance_delta = $balanceDelta;
        $historyEntry->source = $this->source;
        $historyEntry->source_key = $this->sourceKey;
        $historyEntry->source_desc = $this->sourceDesc;
        $historyEntry->balance_before = $event?->balanceBefore ?? ($user->money - $balanceDelta);
        $historyEntry->balance_after = $event?->balanceAfter ?? $user->money;
        $historyEntry->actor_id = $event?->actor?->id ?? $user->id;
        $historyEntry->created_at = Carbon::now()->tz($this->storeTimezone);
        $historyEntry->save();
    }
}
