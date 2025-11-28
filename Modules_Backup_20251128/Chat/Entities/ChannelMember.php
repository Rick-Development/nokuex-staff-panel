<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'channel_id',
        'user_id',
        'role',
        'joined_at'
    ];

    protected $casts = [
        'joined_at' => 'datetime'
    ];

    public function channel(): BelongsTo
    {
        return $this->belongsTo(ChatChannel::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\Modules\Core\Entities\Staff::class, 'user_id');
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\ChannelMemberFactory::new();
    }
}