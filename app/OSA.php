<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class OSA extends Model
{
    protected $table = 'osa';
    protected $primaryKey = 'user_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
