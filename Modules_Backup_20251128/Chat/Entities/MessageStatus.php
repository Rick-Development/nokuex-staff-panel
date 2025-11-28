<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'user_id',
        'status'
    ];

    public $timestamps = false;

    protected $casts = [
        'updated_at' => 'datetime'
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\Modules\Core\Entities\Staff::class, 'user_id');
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\MessageStatusFactory::new();
    }
}