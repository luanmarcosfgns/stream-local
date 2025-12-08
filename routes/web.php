<?php

use App\Http\Controllers\FileServeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CollectionVideoController;
Route::get('/',function(){
   return redirect('/collections/Filmes');
});
use Illuminate\Support\Facades\Http;



Route::get('/collections/{path}', [CollectionVideoController::class, 'show'])
    ->where('path', '.*')
    ->name('collections.show');
Route::get('/file/{path}', [FileServeController::class, 'serve'])
    ->where('path', '.*')
    ->name('file.serve');

Route::get('/cleanup-m3u', [\App\Http\Controllers\M3uCleanupController::class, 'cleanup']);
