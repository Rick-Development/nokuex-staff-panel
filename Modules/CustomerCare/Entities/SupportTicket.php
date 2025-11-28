<?php

namespace Modules\CustomerCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CustomerCare\Database\Factories\SupportTicketFactory;

use Modules\Core\Entities\Staff;
use App\Models\User;

class SupportTicket extends Model
{
    use HasFactory;

    protected $table = 'staff_support_tickets';

    protected $fillable = [
        'ticket_number',
        'user_id',
        'assigned_to',
        'subject',
        'description',
        'status',
        'priority',
        'category',
        'first_response_at',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class, 'ticket_id');
    }
}
