<?php
namespace App\AppServices;

use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Exception;


class WalletService
{
    public function __construct(private WalletRepository $walletRepository) {
      
        $this->walletRepository = $walletRepository;
        
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
}
