<?php

namespace Modules\CustomerCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CustomerCare\Database\Factories\TicketReplyFactory;

use Modules\Core\Entities\Staff;
use App\Models\User;

class TicketReply extends Model
{
    use HasFactory;

    protected $table = 'staff_ticket_replies';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'staff_id',
        'message',
        'attachments',
        'is_internal_note',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal_note' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
