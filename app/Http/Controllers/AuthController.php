<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppServices\AuthService;

class AuthController extends Controller
{
    public function __construct(AuthService $authService){
       $this->authService = $authService;
    }

    public function register(Request $request){
        return $this->authService->register($request);
    }

    public function login(Request $request){
        return $this->authService->login($request);
    }
}
