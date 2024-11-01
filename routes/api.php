<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('campaign', [App\Http\Controllers\CampaignController::class, 'store'])->name('campaign.create');
Route::get('campaign', [App\Http\Controllers\CampaignController::class, 'index'])->name('campaign.list');
Route::get('campaign/single/{id}', [App\Http\Controllers\CampaignController::class, 'show'])->name('campaign.single');
Route::put('campaign/{id}', [App\Http\Controllers\CampaignController::class, 'update'])->name('campaign.update');