<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StageConfig extends Model
{
    protected $guarded = [];

    protected $casts = [
        'validation_rules' => 'array',
        'allowed_tags' => 'array',
        'inherit_from_parent' => 'boolean',
        'can_edit_geometry' => 'boolean',
        'active' => 'boolean',
    ];

    public $timestamps = true;

    public function drawings(): HasMany
    {
        return $this->hasMany(Drawing::class);
    }
}
