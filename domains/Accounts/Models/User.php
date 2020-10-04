<?php

namespace Domains\Accounts\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use MustVerifyEmail;
    use Notifiable;
    use SoftDeletes;
}
