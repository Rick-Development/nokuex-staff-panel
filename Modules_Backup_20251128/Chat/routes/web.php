<?php

use Illuminate\Support\Facades\Route;
use Modules\Chat\Http\Controllers\ChatController;

Route::prefix('chat')->middleware(['auth:staff'])->group(function() {
    Route::get('/', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/channel/{channelId}', [ChatController::class, 'showChannel'])->name('chat.channel.show');
    Route::post('/channel/{channelId}/message', [ChatController::class, 'sendMessage'])->name('chat.message.send');
    Route::post('/channel/create', [ChatController::class, 'createChannel'])->name('chat.channel.create');
    Route::post('/channel/{channelId}/member', [ChatController::class, 'addMember'])->name('chat.channel.member.add');
});