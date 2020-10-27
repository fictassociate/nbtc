<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user_pwd';

    protected $fillable = ['USER_LOGIN', 'PASSWORD'];
}
