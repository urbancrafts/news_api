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

Route::post('user/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.api');
Route::post('user/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.api');

Route::group(['middleware' => 'checkUser'], function () {

    Route::prefix('auth/user')->group(function(){
        Route::get('fetch_users', [App\Http\Controllers\UserController::class, 'index'])->name('users.api');
        Route::get('profile', [App\Http\Controllers\UserController::class, 'myProfile'])->name('profile.api');
        Route::get('fetch_wallets', [App\Http\Controllers\UserController::class, 'fetchWallets'])->name('wallets.api');
        Route::get('fetch_wallet/{id}', [App\Http\Controllers\UserController::class, 'fetchSignleWallet'])->name('wallet.api');
        Route::post('transact', [App\Http\Controllers\TransactionController::class, 'transact'])->name('transact.api');
        
    });

});


// Route::get('campaign/single/{id}', [App\Http\Controllers\CampaignController::class, 'show'])->name('campaign.single');
// Route::put('campaign/{id}', [App\Http\Controllers\CampaignController::class, 'update'])->name('campaign.update');