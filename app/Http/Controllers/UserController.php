<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\AppServices\WalletService;
use JWTAuth;

class UserController extends Controller
{
    //
public function __construct(User $user, Wallet $wallet, Transaction $transact, WalletService $walletService){
  $this->user = $user;
  $this->wallet = $wallet;
  $this->transact = $transact;
  $this->walletService = $walletService;
  $this->profile = JWTAuth::parseToken()->authenticate();
   
  $this->result = (object)array(
    'status' => false,
    'status_code' => 200,
    'message' => null,
    'data' => (object) null,
    'token' => null,
    'debug' => null
);
}


public function index(){
    $user = $this->user->with('wallet')->paginate(20);
     
    $this->result->status = true;
    $this->result->message = "Users fetched.";
    $this->result->data = $user;
    $this->result->status_code = 200;
    return response()->json($this->result, 200);

}

public function myProfile(){
$profile = $this->profile->wallet()->get();

$data = [];
if(count($profile) > 0){
    foreach($profile as $prof){
     $data[] = [
           'id' => $prof->id,
           'currency_type' => $prof->currency_type,
           'monthly_interest_percentage' => $prof->monthly_interest_percentage,
           'balance' => $prof->balance,
           'transaction_history' => (!count($this->transact->where('sender_wallet_id', $prof->id)->orWhere('reciepient_wallet_id', $prof->id)->get()) > 0 ) ? null : $this->walletService->transactionData($this->transact->where('sender_wallet_id', $prof->id)->orWhere('reciepient_wallet_id', $prof->id)->paginate(20))
    ];
  
    }
}

$this->result->status = true;
$this->result->message = "Users fetched.";
$this->result->data = ['user' => $this->profile, 'wallet' => $data];
$this->result->status_code = 200;
return response()->json($this->result, 200);
}

public function fetchWallets(){
    $wallet = $this->wallet->with('user')->paginate(20);
     
    $this->result->status = true;
    $this->result->message = "Wallets fetched.";
    $this->result->data = $wallet;
    $this->result->status_code = 200;
    return response()->json($this->result, 200);
}

public function fetchSignleWallet($id){
    $wallet = $this->wallet->whereId($id)->with('user')->first();
     
    $this->result->status = true;
    $this->result->message = "Wallets fetched.";
    $this->result->data = $wallet;
    $this->result->status_code = 200;
    return response()->json($this->result, 200);
}


}
