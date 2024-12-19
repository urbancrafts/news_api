<?php
namespace App\AppServices;

use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Wallet;
use JWTAuth;

use Exception;


class WalletService
{
    public function __construct(private WalletRepository $walletRepository, Wallet $walletData) {
      
        $this->walletRepository = $walletRepository;
        $this->wallet = $walletData;
        
        $this->result = (object)array(
            'status' => false,
            'status_code' => 200,
            'message' => null,
            'data' => (object) null,
            'token' => null,
            'debug' => null
        );
    }

    public function performTransaction(int $userId, int $wallet_id, float $amount): string
    {
        DB::beginTransaction();

        try {
            // Lock wallet for the current user
            $wallet = $this->walletRepository->findByUserIdForUpdate($wallet_id);

            if ($wallet->balance < $amount) {
                return response()->json(['error' => 'Insufficient balance'], 400); 
            }

            // Update wallet balance
            //$recipientNewBalance = ($userId) ? $wallet->balance + $amount : $wallet->balance - $amount;
            $senderNewBalance = ($wallet) ? $wallet->balance - $amount : $wallet->balance + $amount;


            $this->walletRepository->updateRecipientBalance($wallet, $userId, $amount);
            $this->walletRepository->updateSenderBalance($wallet, $senderNewBalance);
            $this->walletRepository->createTransaction($wallet, $userId, $amount);
            DB::commit();
            return response()->json("Transaction successful. New balance: $senderNewBalance", 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Transaction failed:' . $e->getMessage()], 500); 
            
        }
    }


    public function transact($request){

        $rules = [
            'user_id' => 'required|integer|exists:users,id',
            'sender_wallet_id' => 'required|integer|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01',
            // 'type' => 'required|in:c,deduct',
        ];
        $validator = Validator::make($request->all(), $rules);   
    
            if ($validator->fails()) {
                $this->result->status = false;
                $this->result->message = "Sorry a Validation Error Occured";
                $this->result->data->errors = $validator->errors()->all();
                $this->result->status_code = 422;
                return response()->json($this->result, 422);
            }

        

       return $response = $this->performTransaction(
            $request['user_id'],
            $request['sender_wallet_id'],
            $request['amount']
            
        );

        //return response()->json(['message' => $response]);
    }

    public function transactionData($transactionData){
        $profile = JWTAuth::parseToken()->authenticate();
        $data = [];
        foreach ($transactionData as $transact){
          $data[] = [
            "id" => $transact->id,
            "sender_wallet_id" => $transact->sender_wallet_id,
            "reciepient_wallet_id" => $transact->reciepient_wallet_id,
            "amount" => $transact->amount,
            "status" => $transact->status,
            "created_at" => $transact->created_at,
            "updated_at" => $transact->updated_at,
            'sender' => ($this->checkUser($transact->sender_wallet_id) != $profile->id) ? $this->getUser($transact->sender_wallet_id) : 'Me',
            'reciepient' => ($this->checkUser($transact->reciepient_wallet_id) != $profile->id) ? $this->getUser($transact->reciepient_wallet_id) : 'Me'
          ];
        }
        return $data;

    }

   public function checkUser($data){
    return $this->wallet->whereId($data)->first()->user()->first()->id;
   }

   public function getUser($data){
    return $this->wallet->whereId($data)->first()->user()->first();
   }
}
