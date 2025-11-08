<?php

namespace Modules\Core\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staffs';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'avatar',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function department()
    {
        return $this->role->department ?? null;
    }

    public function hasDepartmentAccess($department)
    {
        if ($this->role && $this->role->permissions && in_array('*', $this->role->permissions)) {
            return true;
        }
        
        return $this->role->department === $department;
    }

    public function hasPermission($permission): bool
    {
        if ($this->role && $this->role->permissions) {
            return in_array($permission, $this->role->permissions) || in_array('*', $this->role->permissions);
        }
        return false;
    }

    public function getDashboardUrl()
    {
        $department = $this->department();
        
        switch ($department) {
            case Role::DEPARTMENT_CUSTOMER_CARE:
                return route('customercare.dashboard');
                
            case Role::DEPARTMENT_SALES:
                return route('sales.dashboard');
                
            case Role::DEPARTMENT_FINANCE:
                return route('finance.dashboard');
                
            case Role::DEPARTMENT_COMPLIANCE:
                return route('compliance.dashboard');
                
            case Role::DEPARTMENT_ADMIN:
            default:
                return route('core.dashboard');
        }
    }

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\StaffFactory::new();
    }
}