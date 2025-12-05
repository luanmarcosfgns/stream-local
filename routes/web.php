<?php

use App\Http\Controllers\FileServeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CollectionVideoController;
Route::get('/',function(){
   return redirect('/collections/Filmes');
});
use Illuminate\Support\Facades\Http;

Route::get('/teste', function () {

    $api_key = env('THEMOVIEDBCHAVE');

    $response = Http::get('https://api.themoviedb.org/3/search/tv', [
        'api_key' => $api_key,
        'query' => 'Ruth e Boaz',
        'language' => 'pt-BR'
    ]);
    dd($response->object());
    if (!empty($response->object()->results[0])) {
        echo '<img width="100"  src="'.'https://image.tmdb.org/t/p/w500'.$response->object()->results[0]->poster_path.'">';
    }
});


Route::get('/collections/{path}', [CollectionVideoController::class, 'show'])
    ->where('path', '.*')
    ->name('collections.show');
Route::get('/file/{path}', [FileServeController::class, 'serve'])
    ->where('path', '.*')
    ->name('file.serve');

