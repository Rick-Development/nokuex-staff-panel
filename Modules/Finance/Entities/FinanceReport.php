<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Core\Entities\Staff;

class FinanceReport extends Model
{
    use HasFactory;

    protected $table = 'finance_reports';

    protected $fillable = [
        'generated_by',
        'report_type',
        'title',
        'start_date',
        'end_date',
        'data',
        'file_path',
    ];

    protected $casts = [
        'data' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function generator()
    {
        return $this->belongsTo(Staff::class, 'generated_by');
    }
}
