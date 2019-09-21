<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;

class Student extends Model
{
    protected $table = 'student';

    public $fillable = [
        'student_id',
        'last_name',
        'first_name',
        'middle_initial'
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class)->withPivot('involvement');
    }
}
