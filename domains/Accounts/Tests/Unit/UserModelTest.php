<?php

namespace Domains\Accounts\Tests\Unit;

use Carbon\Carbon;
use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Enums\AccountTypeEnum;
use Domains\Accounts\Models\User;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    private User $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = UserFactory::new()->unverified()->make();
    }

    /** @test */
    public function it_contains_required_properties(): void
    {
        self::assertIsString($this->model->name);
        self::assertIsString($this->model->email);
        self::assertIsString($this->model->password);
        self::assertNull($this->model->email_verified_at);
        self::assertFalse($this->model->trusted);

        self::assertInstanceOf(Carbon::class, $this->model->created_at);
        self::assertInstanceOf(Carbon::class, $this->model->updated_at);
        self::assertNull($this->model->deleted_at);
    }

    /** @test */
    public function it_uses_correct_table_name(): void
    {
        self::assertEquals('users', $this->model->getTable());
    }

    /** @test */
    public function it_uses_correct_primary_key(): void
    {
        self::assertEquals('id', $this->model->getKeyName());
    }

    /** @test */
    public function it_uses_soft_deletes(): void
    {
        self::assertArrayHasKey(SoftDeletingScope::class, $this->model->getGlobalScopes());
    }

    /** @test */
    public function it_uses_timestamps(): void
    {
        self::assertTrue($this->model->usesTimestamps());
    }

    /** @test */
    public function it_has_a_user_account_type(): void
    {
        self::assertEquals(AccountTypeEnum::USER, $this->model->account_type);
    }
}
