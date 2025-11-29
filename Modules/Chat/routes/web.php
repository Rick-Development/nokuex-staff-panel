<?php

use Modules\Chat\Http\Controllers\ChatController;

Route::group(['middleware' => ['web', 'auth:staff'], 'prefix' => 'staff/chat', 'as' => 'staff.chat.'], function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::post('/general/send', [ChatController::class, 'sendToGeneral'])->name('general.send');
    Route::get('/general/messages', [ChatController::class, 'getGeneralMessages'])->name('general.messages');
    Route::get('/check-new', [ChatController::class, 'checkNewMessages'])->name('check_new');
    Route::get('/{id}', [ChatController::class, 'show'])->name('show');
    Route::get('/{id}/view', [ChatController::class, 'getChatView'])->name('view');
    Route::post('/{id}/send', [ChatController::class, 'send'])->name('send');
    Route::get('/{id}/messages', [ChatController::class, 'getMessages'])->name('messages');
});
