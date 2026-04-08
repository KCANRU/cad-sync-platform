<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Drawing extends Model
{
    protected $guarded = [];

    protected $casts = [
        'rev_no' => 'integer',
        'status' => 'string',
    ];

    public $timestamps = true;

    public function stage(): BelongsTo
    {
        return $this->belongsTo(StageConfig::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Drawing::class, 'parent_drawing_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Drawing::class, 'parent_drawing_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(DrawingAttribute::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function pdfAnnotations(): HasMany
    {
        return $this->hasMany(PdfAnnotation::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
