<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrawingAttribute extends Model
{
    protected $guarded = [];

    protected $casts = [
        'source' => 'string',
        'synced_at' => 'datetime',
    ];

    public $timestamps = true;

    public function drawing(): BelongsTo
    {
        return $this->belongsTo(Drawing::class);
    }
}
