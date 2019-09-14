<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Organization extends Model
{
    protected $table = 'organization';
    protected $primaryKey = 'user_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
