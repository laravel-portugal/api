<?php

namespace Domains\Accounts\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use MustVerifyEmail;
    use Notifiable;
    use SoftDeletes;
    use Authorizable;
    use HasApiTokens;

    protected $hidden = [
        'password', 'trusted', 'email_verified_at', 'deleted_at'
    ];

    protected $casts = [
        'email_verified_at' => 'date',
    ];
}
