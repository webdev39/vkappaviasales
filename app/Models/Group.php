<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'number',
        'confirmation_code'
    ];

    public function user()
    {
        $this->belongsTo(UserGroup::class, 'id', 'group_id');
    }
}

