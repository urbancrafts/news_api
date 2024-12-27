<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('news/')->group(function(){
Route::get('articles', [App\Http\Controllers\NewsController::class, 'index']);
Route::get('filter_articles', [App\Http\Controllers\NewsController::class, 'searchArticles']);
});