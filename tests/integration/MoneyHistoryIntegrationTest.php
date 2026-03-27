<?php

namespace Mattoid\MoneyHistory\Tests\integration;

use AntoineFr\Money\Event\MoneyUpdated;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;
use Mattoid\MoneyHistory\Event\MoneyAllHistoryEvent;
use Mattoid\MoneyHistory\Event\MoneyHistoryEvent;

class MoneyHistoryIntegrationTest extends TestCase
{
    use RetrievesAuthorizedUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension('mattoid-money-history');

        $this->prepareDatabase([
            'users' => [
                $this->normalUser(),
                ['id' => 3, 'username' => 'bob', 'email' => 'bob@example.com'],
            ],
        ]);

        $this->app();
    }

    /** @test */
    public function it_records_money_updated_events_with_expected_history_fields(): void
    {
        $dispatcher = $this->app()->getContainer()->make(Dispatcher::class);
        $user = User::query()->findOrFail(2);
        $actor = User::query()->findOrFail(3);

        $dispatcher->dispatch(new MoneyUpdated(
            $user,
            5.5,
            'POST_REWARD',
            'money.post-reward',
            [],
            $actor,
            $user,
            10.0,
            15.5
        ));

        $historyEntry = $this->connection()->table('user_money_history')->where('user_id', $user->id)->first();

        $this->assertNotNull($historyEntry);
        $this->assertEquals(5.5, (float) $historyEntry->balance_delta);
        $this->assertSame('POST_REWARD', $historyEntry->source);
        $this->assertSame('money.post-reward', $historyEntry->source_key);
        $this->assertSame([], json_decode($historyEntry->source_params, true));
        $this->assertEquals(10.0, (float) $historyEntry->balance_before);
        $this->assertEquals(15.5, (float) $historyEntry->balance_after);
        $this->assertSame($actor->id, $historyEntry->actor_id);
    }

    /** @test */
    public function it_stores_source_params_as_json_and_returns_them_for_variable_transaction_reasons(): void
    {
        $dispatcher = $this->app()->getContainer()->make(Dispatcher::class);
        $user = User::query()->findOrFail(2);
        $actor = User::query()->findOrFail(3);
        $sourceParams = [
            'itemName' => 'VIP Badge',
            'itemTypeKey' => 'money-store.forum.item-types.decoration',
            'purchaseTypeKey' => 'money-store.forum.purchase-types.auto-renew',
            'purchaseCount' => 2,
        ];

        $dispatcher->dispatch(new MoneyUpdated(
            $user,
            -12.5,
            'AUTO_RENEW',
            'money-store.forum.reason.auto-renew',
            $sourceParams,
            $actor,
            $user,
            50.0,
            37.5
        ));

        $historyEntry = $this->connection()->table('user_money_history')->where('user_id', $user->id)->first();

        $this->assertNotNull($historyEntry);
        $this->assertSame('money-store.forum.reason.auto-renew', $historyEntry->source_key);
        $this->assertIsString($historyEntry->source_params);
        $this->assertJson($historyEntry->source_params);
        $this->assertJsonStringEqualsJsonString(
            json_encode($sourceParams, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            $historyEntry->source_params
        );
        $this->assertSame($sourceParams, json_decode($historyEntry->source_params, true));

        $response = $this->send(
            $this->request('GET', '/api/users/2/money/history', ['authenticatedAs' => 2])
        );

        $payload = json_decode((string) $response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(1, $payload['data']);
        $this->assertSame('money-store.forum.reason.auto-renew', $payload['data'][0]['attributes']['source_key']);
        $this->assertSame($sourceParams, $payload['data'][0]['attributes']['source_params']);
    }

    /** @test */
    public function it_records_legacy_events_and_lists_entries_in_descending_order(): void
    {
        $dispatcher = $this->app()->getContainer()->make(Dispatcher::class);
        $user = User::query()->findOrFail(2);
        $actor = User::query()->findOrFail(3);

        $user->setAttribute('money', 20);

        $dispatcher->dispatch(new MoneyHistoryEvent(
            $user,
            3,
            'LEGACY_SINGLE',
            'money.legacy-single',
            [],
            $actor,
            17,
            20
        ));

        $dispatcher->dispatch(new MoneyAllHistoryEvent(
            [$user],
            -2,
            'LEGACY_BATCH',
            'money.legacy-batch',
            [],
            $actor
        ));

        $records = $this->connection()->table('user_money_history')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($record) {
                $record->source_params = $record->source_params ? json_decode($record->source_params, true) : null;

                return $record;
            })
            ->values();

        $this->assertCount(2, $records);
        $this->assertSame('LEGACY_BATCH', $records[0]->source);
        $this->assertSame([], $records[0]->source_params);
        $this->assertSame('LEGACY_SINGLE', $records[1]->source);
        $this->assertSame([], $records[1]->source_params);

        $response = $this->send(
            $this->request('GET', '/api/users/2/money/history', ['authenticatedAs' => 2])
        );

        $payload = json_decode((string) $response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $payload['data']);
        $this->assertSame('LEGACY_BATCH', $payload['data'][0]['attributes']['source']);
        $this->assertSame([], $payload['data'][0]['attributes']['source_params']);
        $this->assertSame('LEGACY_SINGLE', $payload['data'][1]['attributes']['source']);
        $this->assertSame([], $payload['data'][1]['attributes']['source_params']);
    }

    /** @test */
    public function it_does_not_record_zero_delta_or_missing_user_events(): void
    {
        $dispatcher = $this->app()->getContainer()->make(Dispatcher::class);
        $user = User::query()->findOrFail(2);

        $dispatcher->dispatch(new MoneyUpdated(
            $user,
            0.0,
            'ZERO_DELTA',
            'money.zero-delta'
        ));

        $dispatcher->dispatch(new MoneyUpdated(
            null,
            5.0,
            'MISSING_USER',
            'money.missing-user'
        ));

        $this->assertSame(0, $this->connection()->table('user_money_history')->count());
    }

    /** @test */
    public function it_denies_other_users_history_without_permission(): void
    {
        $response = $this->send(
            $this->request('GET', '/api/users/3/money/history', ['authenticatedAs' => 2])
        );

        $this->assertEquals(403, $response->getStatusCode());
    }

    private function connection(): ConnectionInterface
    {
        return $this->app()->getContainer()->make(ConnectionInterface::class);
    }
}
