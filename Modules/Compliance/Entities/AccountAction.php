<?php

namespace Modules\Compliance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Modules\Core\Entities\Staff;

class AccountAction extends Model
{
    use HasFactory;

    protected $table = 'account_actions';

    protected $fillable = [
        'user_id',
        'staff_id',
        'action_type',
        'reason',
        'internal_notes',
        'action_expires_at',
        'is_active',
        'reversed_by',
        'reversed_at',
    ];

    protected $casts = [
        'action_expires_at' => 'datetime',
        'reversed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function reversedBy()
    {
        return $this->belongsTo(Staff::class, 'reversed_by');
    }
}
