<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Modules\Core\Entities\Staff;

class CrmLead extends Model
{
    use HasFactory;

    protected $table = 'crm_leads';

    protected $fillable = [
        'user_id',
        'assigned_to',
        'name',
        'email',
        'phone',
        'status',
        'source',
        'notes',
        'last_contact_at',
        'next_follow_up_at',
    ];

    protected $casts = [
        'last_contact_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }
}
