<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\JsonRequest;
use App\Model\EmployerModel;
use App\Model\EndUserModel;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use phplusir\smsir\Smsir;
use PHPUnit\Framework\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{

    public function index()
    {
        return 'OK';
    }

    public function contest()
    {
        return response()->json(['status'=>'ok'], 200);
    }

    public function store(User $Model,Request $request)
    {
//        dd(bcrypt('Separesh@1397!@#'));
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $input_phone = $request->json()->get('phone');
            $input_email = $request->json()->get('email');
            $input_password = $request->json()->get('password');
            $input_name = $request->json()->get('name');
            $input_last_name = $request->json()->get('last_name');

            $ValidStr = ['phone'=>$input_phone,'email'=>$input_email,'password'=>$input_password,'name'=>$input_name,'last_name'=>$input_last_name];

            $validation = Validator::make($ValidStr,[
                'phone' => 'required|unique:users|regex:/(09)[0-9]{9}/',
                'email' => 'email|nullable|unique:users',
                'password' => 'required',
                'name' => 'required',
                'last_name' => 'required',
            ]);

            if($validation->fails()){
                $errors = $validation->errors();
//
                return response()->json(['code'=>'1001','message'=>'Validation Error','errors'=>$errors], 422);
            } else {
                $DataList = [
                    'name' => $input_name,
                    'last_name' => $input_last_name,
                    'phone' => $input_phone,
                    'email' => $input_phone.'@gmail.com',
                    'email2' => $input_email,
                    'password' => bcrypt($input_password),
                ];

                try {
                    $userEntity = $Model::create($DataList);

                    $credentials = ['email' => $DataList['email'], 'password' => $input_password];

                    $token = JWTAuth::attempt($credentials);

                    return response()->json(['code'=>'1002','message'=>'successfully registered','token' => $token,'user'=>json_decode($userEntity,true)], 200);

                } catch (QueryException $ex) {
                    $string = $ex->getMessage();
                    return response()->json(['code'=>'1004','message'=>'error while inserting data','errors'=>$string], 400);
                }
            }
        } else {

            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }

//        dd(bcrypt('Separesh@1397!@#'));
     }

    public function checkphone(User $Model,Request $request)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $input_phone = $request->json()->get('phone');

            $DataList = $Model::where('phone', $input_phone)->get()->pluck('id')->tojson();
            if (json_decode($DataList, true)) {
                return response()->json(['code'=>'2001','message'=>'the phone has already been existed','status' => 'true'], 200);
            } else {
                return response()->json(['code'=>'2002','message'=>'the phone not found','status' => 'false'], 200);
            }
        } else {

            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }

    }

    public function getdata(Request $request, EmployerModel $Model)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $User = $this->getAuthenticatedUser();
            $Employer = $Model::first();

            return response()->json(['code'=>'1002','message'=>'user data was successfully received', 'user'=>json_decode($User,true), 'employer'=>json_decode($Employer,true)], 200);
        } else {
            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }
    }

    public function checkpass(User $Model,Request $request)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $input_phone = $request->json()->has('phone')? $request->json()->get('phone') : "0";
            $input_password = $request->json()->has('password')? $request->json()->get('password') : "0";
            $userEntity = $Model::where('phone', $input_phone)->first();
            if (Hash::check($input_password, $userEntity->password))
            {
                $credentials = ['email' => $userEntity->email, 'password' => $input_password];

                $token = JWTAuth::attempt($credentials);

                return response()->json(['code'=>'1002','message'=>'you are registered','token' => $token,'user'=>json_decode($userEntity,true)], 200);
            } else {
                return response()->json(['code'=>'1005','message'=>'incorrect password','errors' => 'you are not authorized'], 200);
            }
        } else {
            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }
    }

    public function getcode(Request $request)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $input_phone = $request->json()->get('phone');
            $ValidStr = ['phone'=>$input_phone];

            $validation = Validator::make($ValidStr,[
                'phone' => 'required|regex:/(09)[0-9]{9}/',
            ]);

            if($validation->fails()){
                $errors = $validation->errors();
                return response()->json(['code'=>'1001','message'=>'Validation Error','errors'=>$errors], 422);
            } else{
                $rand_number=random_int(10000, 99999);
                try{
                Smsir::sendVerification($rand_number,$input_phone);
                return response()->json(['code'=>'1002','message'=>'verification code is sent','VerifyCode' => $rand_number], 200);
                } catch (Exception $e) {
                    return response()->json(['code'=>'1008','message'=>'sms not sent','errors'=>'sms problem'], 400);
                }
            }


        } else {
            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['code'=>'1003','message'=>'user_not_found','errors'=>'you are not authorized'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        // the token is valid and we have found the user via the sub claim
//        return response()->json(compact('user'));
        return $user;
    }

    public function chanepass(User $Model,Request $request)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $input_password = $request->json()->get('password');
            $User = $this->getAuthenticatedUser();
            $DataList = [
                'password' => bcrypt($input_password),
            ];
            $ListItem = $Model::find($User->id);
            try {
                $ListItem->update($DataList);
                $credentials = ['email' => $ListItem->email, 'password' => $input_password];
                $token = JWTAuth::attempt($credentials);
                return response()->json(['code'=>'1002','message'=>'your password was successfully changed','token' => $token], 200);
            } catch (QueryException $ex) {
                $string = $ex->getMessage();
                return response()->json(['code'=>'1004','message'=>'error while inserting data','errors'=>$string], 400);
            }

        } else {
            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }
    }

    public function forgetpass(User $Model,Request $request)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $input_password = $request->json()->get('password');
            $input_phone = $request->json()->get('phone');
            $DataList = [
                'password' => bcrypt($input_password),
                'phone' => $input_phone,
            ];
            $ListItem = $Model::where('phone', $input_phone)->first();
            try {
                $ListItem->update($DataList);
                $credentials = ['email' => $ListItem->email, 'password' => $input_password];
                $token = JWTAuth::attempt($credentials);
                return response()->json(['code'=>'1002','message'=>'your password was successfully changed','token' => $token], 200);
            } catch (QueryException $ex) {
                $string = $ex->getMessage();
                return response()->json(['code'=>'1004','message'=>'error while inserting data','errors'=>$string], 400);
            }

        } else {
            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }
    }

    public function walletcharge(Request $request)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $User = $this->getAuthenticatedUser();
            $input_wallet = $request->json()->get('wallet');

//            DB::table('users')->whereId($User->id)->increment('wallet','');

                try{
                    User::find($User->id)->increment('wallet',$input_wallet);
                    $Wallet = User::find($User->id)->wallet;
                    return response()->json(['code'=>'1002','message'=>'your wallet is increased successfully','wallet' => $Wallet], 200);
                } catch (QueryException $ex) {
                    $string = $ex->getMessage();
                    return response()->json(['code'=>'1004','message'=>'error while inserting data','errors'=>$string], 400);
                }


        } else {
            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }
    }

    public function updateprofile(User $Model,Request $request)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $input_name = $request->json()->get('name');
            $input_last_name = $request->json()->get('last_name');
            $User = $this->getAuthenticatedUser();

            $DataList = ['name' => $input_name, 'last_name' => $input_last_name];
            


                return response()->json(['code'=>'1002','message'=>'you are registered','token' => $token,'user'=>json_decode($userEntity,true)], 200);

        } else {
            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }
    }

}
