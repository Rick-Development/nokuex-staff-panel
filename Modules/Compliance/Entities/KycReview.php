<?php

namespace Modules\Compliance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Modules\Core\Entities\Staff;

class KycReview extends Model
{
    use HasFactory;

    protected $table = 'kyc_reviews';

    protected $fillable = [
        'user_id',
        'reviewed_by',
        'review_type',
        'status',
        'rejection_reason',
        'documents_checked',
        'notes',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'documents_checked' => 'array',
        'submitted_at' => 'datetime',
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
