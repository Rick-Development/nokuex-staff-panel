<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatChannel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'department_id',
        'created_by'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\Modules\Core\Entities\Staff::class, 'created_by');
    }

    public function members(): HasMany
    {
        return $this->hasMany(ChannelMember::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\ChatChannelFactory::new();
    }
}