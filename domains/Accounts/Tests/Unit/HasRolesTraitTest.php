<?php

namespace Domains\Accounts\Tests\Unit;

use Domains\Accounts\Database\Factories\UserFactory;
use Domains\Accounts\Enums\AccountTypeEnum;
use Domains\Accounts\Models\User;
use Tests\TestCase;

class HasRolesTraitTest extends TestCase
{
    private User $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = UserFactory::new()->unverified()->make();
    }

    /** @test */
    public function it_has_user_role(): void
    {
        self::assertTrue($this->model->isOfRole(AccountTypeEnum::USER));
        self::assertTrue($this->model->hasRole(AccountTypeEnum::USER));

        self::assertFalse($this->model->isOfRole(AccountTypeEnum::EDITOR));
        self::assertFalse($this->model->isOfRole(AccountTypeEnum::ADMIN));
        self::assertFalse($this->model->hasRole(AccountTypeEnum::EDITOR));
        self::assertFalse($this->model->hasRole(AccountTypeEnum::ADMIN));
    }

    /** @test */
    public function it_has_editor_role(): void
    {
        $this->model = UserFactory::new()->unverified()->editor()->make();

        self::assertTrue($this->model->isOfRole(AccountTypeEnum::EDITOR));
        self::assertTrue($this->model->hasRole(AccountTypeEnum::EDITOR));
        self::assertTrue($this->model->hasRole(AccountTypeEnum::USER));

        self::assertFalse($this->model->isOfRole(AccountTypeEnum::USER));
        self::assertFalse($this->model->isOfRole(AccountTypeEnum::ADMIN));
        self::assertFalse($this->model->hasRole(AccountTypeEnum::ADMIN));
    }

    /** @test */
    public function it_has_admin_role(): void
    {
        $this->model = UserFactory::new()->unverified()->admin()->make();

        self::assertTrue($this->model->isOfRole(AccountTypeEnum::ADMIN));
        self::assertTrue($this->model->hasRole(AccountTypeEnum::ADMIN));
        self::assertTrue($this->model->hasRole(AccountTypeEnum::EDITOR));
        self::assertTrue($this->model->hasRole(AccountTypeEnum::USER));

        self::assertFalse($this->model->isOfRole(AccountTypeEnum::EDITOR));
        self::assertFalse($this->model->isOfRole(AccountTypeEnum::USER));
    }
}
