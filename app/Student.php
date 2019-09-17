<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;

class Student extends Model
{
    protected $table = 'student';
    public $incrementing = false;

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
