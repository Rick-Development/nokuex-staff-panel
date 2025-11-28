<?php

namespace Modules\CustomerCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Staff;
use App\Models\User;

class Dispute extends Model
{
    use HasFactory;

    protected $table = 'staff_disputes';

    protected $fillable = [
        'dispute_number',
        'user_id',
        'transaction_id',
        'assigned_to',
        'subject',
        'description',
        'status',
        'priority',
        'disputed_amount',
        'resolution',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'disputed_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }

    // Relationship to Transaction model (assuming it exists in App\Models or similar)
    // public function transaction() { ... }
}
