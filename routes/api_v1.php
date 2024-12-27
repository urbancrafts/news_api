<?php

use Illuminate\Http\Request;
use App\Providers\ApiRouteInjector;
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


// Load API-related files from the 'api' directory and subdirectories
ApiRouteInjector::injectRoutes(__DIR__ . '/api/v1');