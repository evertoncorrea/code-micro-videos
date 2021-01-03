<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
	use SoftDeletes, Traits\Uuid;

	const TYPE_DIRECTOR = 1;
	const TYPE_ACTOR = 2;
	const ALL_TYPES = [CastMember::TYPE_DIRECTOR, CastMember::TYPE_ACTOR];

    protected $fillable = ['name', 'type', 'is_active'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'id' => 'string',
		'is_active' => 'boolean',
		'type' => 'smallint'
    ];
    public $incrementing = false;
}
