<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Event;

class Speaker extends Model
{
    protected $table = 'speaker';
    public $fillable = [
        'first_name',
        'last_name',
        'description'
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
