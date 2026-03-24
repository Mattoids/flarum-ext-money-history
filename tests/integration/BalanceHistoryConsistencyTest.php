<?php

namespace Mattoid\MoneyHistory\Tests\integration;

use AntoineFr\Money\Service\BalanceManager;
use Flarum\Testing\integration\RetrievesAuthorizedUsers;
use Flarum\Testing\integration\TestCase;
use Flarum\User\User;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Schema\Blueprint;

class BalanceHistoryConsistencyTest extends TestCase
{
    use RetrievesAuthorizedUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension('antoinefr-money');
        $this->extension('mattoid-money-history');

        $this->prepareDatabase([
            'users' => [
                $this->normalUser(),
                ['id' => 3, 'username' => 'bob', 'email' => 'bob@example.com'],
            ],
        ]);

        $this->app();
        $this->prepareMoneySchema();
    }

    /** @test */
    public function it_keeps_money_and_history_in_sync_across_multiple_balance_updates(): void
    {
        $user = User::query()->findOrFail(2);
        $actor = User::query()->findOrFail(3);

        $balanceManager = new BalanceManager(
            $this->app()->getContainer()->make(ConnectionInterface::class),
            $this->app()->getContainer()->make(Dispatcher::class)
        );

        $this->assertTrue($balanceManager->adjustBalance(
            $user,
            25.0,
            'MANUAL_CREDIT',
            'test.manual-credit',
            ['step' => 'credit'],
            $actor,
            $user
        ));

        $user->refresh();

        $this->assertTrue($balanceManager->adjustBalance(
            $user,
            -10.0,
            'MANUAL_DEBIT',
            'test.manual-debit',
            ['step' => 'debit'],
            $actor,
            $user
        ));

        $user->refresh();
        $connection = $this->app()->getContainer()->make(ConnectionInterface::class);
        $records = $connection
            ->table('user_money_history')
            ->where('user_id', $user->id)
            ->orderBy('id')
            ->get()
            ->map(function ($record) {
                $record->source_params = $record->source_params ? json_decode($record->source_params, true) : null;

                return $record;
            })
            ->values();

        $this->assertEquals(15.0, (float) $user->money);
        $this->assertCount(2, $records);

        $this->assertSame('MANUAL_CREDIT', $records[0]->source);
        $this->assertEquals(25.0, (float) $records[0]->balance_delta);
        $this->assertEquals(0.0, (float) $records[0]->balance_before);
        $this->assertEquals(25.0, (float) $records[0]->balance_after);
        $this->assertSame(['step' => 'credit'], $records[0]->source_params);
        $this->assertSame($actor->id, $records[0]->actor_id);

        $this->assertSame('MANUAL_DEBIT', $records[1]->source);
        $this->assertEquals(-10.0, (float) $records[1]->balance_delta);
        $this->assertEquals(25.0, (float) $records[1]->balance_before);
        $this->assertEquals(15.0, (float) $records[1]->balance_after);
        $this->assertSame(['step' => 'debit'], $records[1]->source_params);
        $this->assertSame($actor->id, $records[1]->actor_id);

        foreach ($records as $record) {
            $this->assertEquals(
                (float) $record->balance_before + (float) $record->balance_delta,
                (float) $record->balance_after
            );
        }

        $this->assertEquals((float) $records[1]->balance_after, (float) $user->money);
    }

    private function prepareMoneySchema(): void
    {
        /** @var ConnectionInterface $connection */
        $connection = $this->app()->getContainer()->make(ConnectionInterface::class);
        $schema = $connection->getSchemaBuilder();

        if (! $schema->hasColumn('users', 'money')) {
            $schema->table('users', function (Blueprint $table) {
                $table->float('money')->default(0);
            });
        }
    }
}
