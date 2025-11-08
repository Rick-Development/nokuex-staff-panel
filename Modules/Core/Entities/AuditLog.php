<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'description',
        'old_values',
        'new_values',
        'model_type',
        'model_id',
        'staff_id',
        'ip_address',
        'user_agent',
        'performed_at'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'performed_at' => 'datetime'
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\AuditLogFactory::new();
    }
}