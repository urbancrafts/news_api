<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppServices\WalletService;

class TransactionController extends Controller
{
 
 public function __construct(WalletService $walletService){
    $this->walletService = $walletService;
 }

 public function transact(Request $request){
    return $this->walletService->transact($request);
 }
 
}
