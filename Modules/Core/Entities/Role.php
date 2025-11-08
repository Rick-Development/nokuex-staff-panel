<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department',
        'description', 
        'permissions',
        'is_active'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean'
    ];

    public function staffs(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    // Department constants
    const DEPARTMENT_CUSTOMER_CARE = 'customer_care';
    const DEPARTMENT_SALES = 'sales';
    const DEPARTMENT_FINANCE = 'finance';
    const DEPARTMENT_COMPLIANCE = 'compliance';
    const DEPARTMENT_ADMIN = 'admin';

    public static function getDepartments()
    {
        return [
            self::DEPARTMENT_ADMIN => 'Administration',
            self::DEPARTMENT_CUSTOMER_CARE => 'Customer Care',
            self::DEPARTMENT_SALES => 'Sales',
            self::DEPARTMENT_FINANCE => 'Finance',
            self::DEPARTMENT_COMPLIANCE => 'Compliance',
        ];
    }

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\RoleFactory::new();
    }
}