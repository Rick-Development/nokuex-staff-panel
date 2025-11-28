<?php

namespace Modules\Compliance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Modules\Core\Entities\Staff;

class ComplianceFlag extends Model
{
    use HasFactory;

    protected $table = 'compliance_flags';

    protected $fillable = [
        'user_id',
        'reviewed_by',
        'flag_type',
        'severity',
        'status',
        'description',
        'metadata',
        'resolution_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Staff::class, 'reviewed_by');
    }
}
