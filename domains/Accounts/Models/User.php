<?php

namespace Domains\Accounts\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model
{
    use MustVerifyEmail;
    use Notifiable;
    use SoftDeletes;
    use Authorizable;

    protected $casts = [
        'email_verified_at' => 'date',
    ];
}
