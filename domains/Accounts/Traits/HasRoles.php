<?php

namespace Domains\Accounts\Traits;

use Domains\Accounts\Enums\AccountTypeEnum;

trait HasRoles
{
    public function isOfRole(string $role): bool
    {
        return $this->account_type === $role;
    }

    public function hasRole(string $role): bool
    {
        $roles = [];
        switch ($this->account_type) {
            case AccountTypeEnum::ADMIN:
                $roles[] = AccountTypeEnum::ADMIN;
            case AccountTypeEnum::EDITOR:
                $roles[] = AccountTypeEnum::EDITOR;
            case AccountTypeEnum::USER:
                $roles[] = AccountTypeEnum::USER;
        }

        return in_array($role, $roles);
    }
}
