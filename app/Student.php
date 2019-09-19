<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;

class Student extends Model
{
    protected $table = 'student';
    public $incrementing = false;

    public $fillable = [
        'id',
        'last_name',
        'first_name',
        'middle_initial'
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
