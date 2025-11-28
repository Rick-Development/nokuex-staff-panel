<?php

namespace Modules\CustomerCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Staff;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'customer_id',
        'subject',
        'description',
        'priority',
        'status',
        'assigned_to',
        'resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }

    protected static function newFactory()
    {
        return \Modules\CustomerCare\Database\factories\SupportTicketFactory::new();
    }
}