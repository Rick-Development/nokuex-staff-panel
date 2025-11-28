<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'sender_id',
        'message',
        'message_type',
        'file_path'
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(ChatChannel::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(\Modules\Core\Entities\Staff::class, 'sender_id');
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(MessageStatus::class);
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\ChatMessageFactory::new();
    }
}