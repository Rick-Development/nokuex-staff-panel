<?php

namespace Modules\CustomerCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }

    protected static function newFactory()
    {
        return \Modules\CustomerCare\Database\factories\CustomerFactory::new();
    }
}