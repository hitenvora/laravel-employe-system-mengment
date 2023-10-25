<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLoginTimes extends Model
{
    use HasFactory;

    protected $table = 'user_login_times';

    protected $fillable = ['user_id', 'last_login_at', 'last_logout_at'];

    public function name_of_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
