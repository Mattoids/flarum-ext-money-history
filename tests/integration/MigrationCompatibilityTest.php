<?php

namespace Mattoid\MoneyHistory\Tests\integration;

use Flarum\Testing\integration\TestCase;
use Illuminate\Database\ConnectionInterface;

class MigrationCompatibilityTest extends TestCase
{
    protected function tearDown(): void
    {
        /** @var ConnectionInterface $connection */
        $connection = $this->app()->getContainer()->make(ConnectionInterface::class);
        $schema = $connection->getSchemaBuilder();

        if ($schema->hasTable('user_money_history')) {
            $schema->drop('user_money_history');
        }
        $create = require __DIR__.'/../../migrations/2023_11_08_000000_create_user_money_history_table.php';
        $source = require __DIR__.'/../../migrations/2024_03_05_000000_add_source_key_to_user_money_history.php';
        $rename = require __DIR__.'/../../migrations/2026_03_22_000000_rename_money_history_columns.php';
        $normalize = require __DIR__.'/../../migrations/2026_03_22_000001_normalize_money_history_balance_delta.php';
        $normalizeSources = require __DIR__.'/../../migrations/2026_04_04_000000_normalize_money_history_source_names.php';

        $create['up']($schema);
        $source['up']($schema);
        $rename['up']($schema);
        $normalize['up']($schema);
        $normalizeSources['up']($schema);

        try {
            parent::tearDown();
        } catch (\Exception $e) {
            // Expected: DDL auto-commits the MySQL transaction,
            // so parent's rollBack() always throws here.
        }
    }

    /** @test */
    public function it_migrates_legacy_money_history_rows_to_the_current_schema_without_data_loss(): void
    {
        /** @var ConnectionInterface $connection */
        $connection = $this->app()->getContainer()->make(ConnectionInterface::class);
        $schema = $connection->getSchemaBuilder();

        if ($schema->hasTable('user_money_history')) {
            $schema->drop('user_money_history');
        }

        $createMigration = require __DIR__.'/../../migrations/2023_11_08_000000_create_user_money_history_table.php';
        $sourceKeyMigration = require __DIR__.'/../../migrations/2024_03_05_000000_add_source_key_to_user_money_history.php';
        $renameMigration = require __DIR__.'/../../migrations/2026_03_22_000000_rename_money_history_columns.php';
        $normalizeBalanceDeltaMigration = require __DIR__.'/../../migrations/2026_03_22_000001_normalize_money_history_balance_delta.php';
        $normalizeSourcesMigration = require __DIR__.'/../../migrations/2026_04_04_000000_normalize_money_history_source_names.php';

        $createMigration['up']($schema);

        $connection->table('user_money_history')->insert([
            [
                'id' => 1,
                'user_id' => 10,
                'type' => 'C',
                'money' => 15.25,
                'source' => 'POSTWASPOSTED',
                'source_desc' => 'legacy credit text',
                'balance_money' => 20,
                'last_money' => 35.25,
                'create_user_id' => 99,
                'change_time' => '2026-03-22 10:11:12',
            ],
            [
                'id' => 2,
                'user_id' => 11,
                'type' => 'D',
                'money' => 7.5,
                'source' => 'POSTWASLIKED',
                'source_desc' => 'legacy debit text',
                'balance_money' => 30,
                'last_money' => 22.5,
                'create_user_id' => 98,
                'change_time' => '2026-03-22 11:12:13',
            ],
        ]);

        $sourceKeyMigration['up']($schema);
        $renameMigration['up']($schema);
        $normalizeBalanceDeltaMigration['up']($schema);
        $normalizeSourcesMigration['up']($schema);

        $this->assertTrue($schema->hasColumn('user_money_history', 'balance_delta'));
        $this->assertTrue($schema->hasColumn('user_money_history', 'balance_before'));
        $this->assertTrue($schema->hasColumn('user_money_history', 'balance_after'));
        $this->assertTrue($schema->hasColumn('user_money_history', 'actor_id'));
        $this->assertTrue($schema->hasColumn('user_money_history', 'created_at'));
        $this->assertTrue($schema->hasColumn('user_money_history', 'source_key'));
        $this->assertTrue($schema->hasColumn('user_money_history', 'source_params'));
        $this->assertFalse($schema->hasColumn('user_money_history', 'type'));
        $this->assertFalse($schema->hasColumn('user_money_history', 'money'));
        $this->assertFalse($schema->hasColumn('user_money_history', 'balance_money'));
        $this->assertFalse($schema->hasColumn('user_money_history', 'last_money'));
        $this->assertFalse($schema->hasColumn('user_money_history', 'create_user_id'));
        $this->assertFalse($schema->hasColumn('user_money_history', 'change_time'));
        $this->assertFalse($schema->hasColumn('user_money_history', 'source_desc'));

        $creditRow = $connection->table('user_money_history')->where('id', 1)->first();
        $debitRow = $connection->table('user_money_history')->where('id', 2)->first();

        $this->assertSame(10, $creditRow->user_id);
        $this->assertEquals(15.25, (float) $creditRow->balance_delta);
        $this->assertEquals(20.0, (float) $creditRow->balance_before);
        $this->assertEquals(35.25, (float) $creditRow->balance_after);
        $this->assertSame(99, $creditRow->actor_id);
        $this->assertSame('POST_POSTED', $creditRow->source);
        $this->assertNull($creditRow->source_key);
        $this->assertNull($creditRow->source_params);
        $this->assertSame('2026-03-22 10:11:12', (string) $creditRow->created_at);

        $this->assertSame(11, $debitRow->user_id);
        $this->assertEquals(-7.5, (float) $debitRow->balance_delta);
        $this->assertEquals(30.0, (float) $debitRow->balance_before);
        $this->assertEquals(22.5, (float) $debitRow->balance_after);
        $this->assertSame(98, $debitRow->actor_id);
        $this->assertSame('POST_LIKED', $debitRow->source);
        $this->assertNull($debitRow->source_key);
        $this->assertNull($debitRow->source_params);
        $this->assertSame('2026-03-22 11:12:13', (string) $debitRow->created_at);
    }
}
