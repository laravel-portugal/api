<?php

namespace Domains\Accounts\Models;

use Domains\Accounts\Traits\HasRoles;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use MustVerifyEmail;
    use Notifiable;
    use SoftDeletes;
    use Authorizable;
    use HasRoles;

    protected $casts = [
        'email_verified_at' => 'date',
    ];

    public function isTrusted(): bool
    {
        return $this->trusted;
    }
}
