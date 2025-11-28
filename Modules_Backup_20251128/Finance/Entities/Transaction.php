<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CustomerCare\Entities\Customer;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'type',
        'amount',
        'currency',
        'status',
        'customer_id',
        'description',
        'metadata',
        'processed_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'processed_at' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function blusaltTransaction()
    {
        return $this->hasOne(BlusaltTransaction::class);
    }
}

// File: Modules/Finance/Entities/BlusaltTransaction.php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlusaltTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'blusalt_reference',
        'transaction_id',
        'status',
        'blusalt_response'
    ];

    protected $casts = [
        'blusalt_response' => 'array'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

// File: Modules/Finance/Entities/Reconciliation.php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Staff;

class Reconciliation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reconciliation_date',
        'expected_amount',
        'actual_amount',
        'variance',
        'status',
        'notes',
        'processed_by'
    ];

    protected $casts = [
        'reconciliation_date' => 'date'
    ];

    public function processedBy()
    {
        return $this->belongsTo(Staff::class, 'processed_by');
    }
}