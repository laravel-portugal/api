<?php

namespace Domains\Accounts\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class User extends Model
{
    use MustVerifyEmail, Notifiable;
}
