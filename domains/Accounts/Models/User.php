<?php

namespace Domains\Accounts\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Model implements Authenticatable
{
    use MustVerifyEmail;
    use Notifiable;
    use SoftDeletes;
    use Authorizable;
    use HasApiTokens;
    use AuthenticableTrait;

    protected $hidden = [
        'password', 'trusted', 'email_verified_at', 'deleted_at'
    ];

    protected $casts = [
        'email_verified_at' => 'date',
    ];
}
