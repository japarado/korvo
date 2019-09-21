<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Organization;
use App\SOCC;
use App\OSA;
use App\Speaker;

class Event extends Model
{
    use SoftDeletes;
    protected $table = 'event';

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'user_id');
    }

    public function socc()
    {
        return $this->belongsTo(SOCC::class, 'socc_id', 'user_id');
    }

    public function osa()
    {
        return $this->belongsTo(OSA::class, 'osa_id', 'user_id');
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class)->withPivot('involvement');
    }
}
