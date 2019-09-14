<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Organization;
use App\SOOC;
use App\OSA;

class Event extends Model
{
    use SoftDeletes;
    protected $table = 'event';

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'user_id');
    }
}
