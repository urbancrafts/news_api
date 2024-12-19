<?php
namespace App\AppServices;

use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthService {

    public function __construct(User $user){
        $this->user = $user;

        $this->result = (object)array(
            'status' => false,
            'status_code' => 200,
            'message' => null,
            'data' => (object) null,
            'token' => null,
            'debug' => null
        );
    }

 public function register($request){
    $rules = $this->signupValidator();
    $validator = Validator::make($request->all(), $rules);   

        if ($validator->fails()) {
            $this->result->status = false;
            $this->result->message = "Sorry a Validation Error Occured";
            $this->result->data->errors = $validator->errors()->all();
            $this->result->status_code = 422;
            return response()->json($this->result, 422);
        }
         
        $data['name'] = $request['name'];
        $data['email'] = $request['email'];
        $data['password'] = Hash::make($request['password']);
        
        $data['remember_token'] = Str::random(10);

        $user = $this->user->create($data);

        $user->wallet()->create([
            'currency_type' => 'NGN',
            'balance' => 5000, 
        ]);
        $user->wallet()->create([
            'currency_type' => 'USD',
            'balance' => 3000, 
        ]);

        $user->wallet()->create([
            'currency_type' => 'EUR',
            'balance' => 3000, 
        ]);

        if (!$user) {
            $this->result->status = false;
            $this->result->message = "Sorry we could not create account at this time. Try again later";
            $this->result->data->error = ['errors' => ['']];
            $this->result->status_code = 500;
            return response()->json($this->result);
        }

        $this->result->status = true;
        $this->result->message = "User account created successfully.";
        $this->result->data->user = $user;
        $this->result->status_code = 200;
        return response()->json($this->result, 200);

 }


 public function signupValidator(){

    return [
        'name' => 'required|string',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ];

 }

 public function login($request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }
        //Request is validated
        // Create token

        
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                $this->result->status = false;
                $this->result->message = "Login credentials are invalid";
                $this->result->data->error = ['errors' => ['']];
                $this->result->status_code = 400;
                return response()->json($this->result);
                // return response_data(false, 400, "Login credentials are invalid.", false, false, false);
            }
        } catch (JWTException $e) {
            // return $credentials;
                $this->result->status = false;
                $this->result->message = "Could not create token";
                $this->result->data->error = ['errors' => ['']];
                $this->result->status_code = 500;
                return response()->json($this->result);
            // return response_data(false, 500, "Could not create token.", false, false, false);
        }

        // at this point we check if the user has 2fa 

        //$this->user = JWTAuth::authenticate($token);

        $auth_user = auth()->authenticate($token);

        $this->result->status = true;
        $this->result->message = "User account created successfully.";
        $this->result->data->user = $auth_user;
        $this->result->token = $token;
        $this->result->status_code = 200;
        return response()->json($this->result, 200);

        // return response_data(true, 200, "Login successful", ['values' => $user], $token, false);
    }

    
}