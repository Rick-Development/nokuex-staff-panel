<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Staff;

class ReconciliationLog extends Model
{
    use HasFactory;

    protected $table = 'reconciliation_logs';

    protected $fillable = [
        'staff_id',
        'reconciliation_type',
        'reconciliation_date',
        'expected_balance',
        'actual_balance',
        'difference',
        'status',
        'notes',
    ];

    protected $casts = [
        'reconciliation_date' => 'date',
        'expected_balance' => 'decimal:2',
        'actual_balance' => 'decimal:2',
        'difference' => 'decimal:2',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
