<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class SOCC extends Model
{
    protected $table = 'socc';
    protected $primaryKey = 'user_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
