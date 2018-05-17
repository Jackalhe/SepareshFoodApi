<?php

namespace App\Http\Controllers\Api\v1;

use App\Model\EndUserModel;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use phplusir\smsir\Smsir;
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

    public function store(User $Model,Request $request)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $validation = Validator::make($request->all(),[
                'phone' => 'required|unique:users|regex:/(09)[0-9]{9}/',
                'email' => 'email|nullable|unique:users',
                'password' => 'required',
                'name' => 'required',
                'last_name' => 'required',
            ]);

            if($validation->fails()){
                $errors = $validation->errors();
//            return $errors->toJson();
                return response()->json(['code'=>'1001','message'=>'Validation Error','errors'=>$errors], 422);
            } else {
                $DataList = [
                    'name' => $request['name'],
                    'last_name' => $request['last_name'],
                    'phone' => $request['phone'],
                    'email' => $request['phone'].'@gmail.com',
                    'email2' => $request['email'],
                    'password' => bcrypt($request['password']),
                ];

                try {
                    $Model::create($DataList);
//                $credentials = $request->only('email', 'password');
                    $credentials = ['email' => $DataList['email'], 'password' => $request['password']];

                    $token = JWTAuth::attempt($credentials);
//                    return response()->json(compact('token'),200);
                    return response()->json(['code'=>'1002','message'=>'successfully registered','token' => $token], 200);

                } catch (QueryException $ex) {
                    $string = $ex->getMessage();
//                    return response()->json(['error' => $string], 401);
                    return response()->json(['code'=>'1004','message'=>'error while inserting data','errors'=>$string], 400);
                }
            }
        } else {

//            return response()->json(['Code' => '2000','Message'=>'you are not authorized'], 401);
            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }

//        dd(bcrypt('Separesh@1397!@#'));
     }

    public function checkphone(User $Model,Request $request)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $DataList = $Model::where('phone', $request['phone'])->get()->pluck('id')->tojson();
            if (json_decode($DataList, true)) {
//                return response()->json(['status' => '1'],200);
                return response()->json(['code'=>'2001','message'=>'the phone has already been existed','status' => '1'], 200);
            } else {
//                return response()->json(['status' => '0'],200);
                return response()->json(['code'=>'2002','message'=>'the phone not found','status' => '0'], 200);
            }
        } else {

            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }

    }

    public function getdata(User $Model,Request $request)
    {
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $User = $this->getAuthenticatedUser();
            return response()->json(['code'=>'1002','message'=>'user data was successfully received', 'user'=>json_decode($User,true)], 200);
        } else {
            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }
    }

//    public function checkpass(User $Model,Request $request)
//    {
//        $decodedAsArray = json_decode( $request['JsonValue'], true );
//        $SecretKey = $request->header('api-secret-key');
//        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
//        {
//            $DataList = [
//                'phone' => $request['phone'],
//                'password' => $request['password'],
//            ];
//
//            $EmailEntity = $Model::where('phone', $DataList['phone'])->pluck('email')->all();
//            $PasswordEntity = $Model::where('phone', $DataList['phone'])->pluck('password')->all();
//
//            if (!isset($PasswordEntity['0'])){
//                $PasswordEntity['0']='0';
//            }
//
//            if (Hash::check($DataList['password'], $PasswordEntity['0']))
//            {
//                $credentials = ['email' => $EmailEntity['0'], 'password' => $DataList['password']];
//
//                $token = JWTAuth::attempt($credentials);
//
//                return response()->json(compact('token'));
//            } else {
//                return response()->json(['token' => 'incorrect password']);
//            }
//        } else {
//            return response()->json(['error' => 'you are not authorized'], 401);
//        }
//
//
//    }

    public function checkpass(User $Model,Request $request)
    {
        $JsonValue = json_decode( $request['JsonValue'], true );
        $SecretKey = $request->header('api-secret-key');
        if (Hash::check(config('Secret.HeaderSecret'), $SecretKey))
        {
            $DataList = [
                'phone' => $JsonValue['phone'],
                'password' => $JsonValue['password'],
            ];

            $EmailEntity = $Model::where('phone', $DataList['phone'])->pluck('email')->all();
            $PasswordEntity = $Model::where('phone', $DataList['phone'])->pluck('password')->all();

            if (!isset($PasswordEntity['0'])){
                $PasswordEntity['0']='0';
            }

            if (Hash::check($DataList['password'], $PasswordEntity['0']))
            {
                $credentials = ['email' => $EmailEntity['0'], 'password' => $DataList['password']];

                $token = JWTAuth::attempt($credentials);

//                return response()->json(compact('token'));
                return response()->json(['code'=>'1002','message'=>'successfully registered','token' => $token], 200);
            } else {
//                return response()->json(['token' => 'incorrect password']);
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
            $validation = Validator::make($request->all(),[
                'phone' => 'required|regex:/(09)[0-9]{9}/',
            ]);

            if($validation->fails()){
                $errors = $validation->errors();
//                return response()->json($errors, 401);
                return response()->json(['code'=>'1001','message'=>'Validation Error','errors'=>$errors], 422);
            } else{
                $rand_number=random_int(10000, 99999);
                Smsir::sendVerification($rand_number,$request['phone']);
//                return response()->json(['code' => $rand_number],200);
                return response()->json(['code'=>'1002','message'=>'verification code is sent','code' => $rand_number], 200);
            }


        } else {
            return response()->json(['code'=>'1003','message'=>'your general token is invalid','errors'=>'you are not authorized'], 401);
        }
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
//                return response()->json(['user_not_found'], 404);
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

    public function checkpass_Json(User $Model,Request $request)
    {
        //return response()->json($request->json()->get('phone'));
        //return response()->json(['result' => 'ok'], 200);
        //$JsonValue = json_decode( $request['phone'], true );
        //return response()->json($JsonValue['phone']);
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

                return response()->json(compact('token'));
            } else {
                return response()->json(['token' => '-1']);
            }
        } else {
            return response()->json(['error' => 'you are not authorized'], 401);
        }
    }
//    public function edit($id)
//    {
//        //
//    }
//
//
//    public function update(Request $request, $id)
//    {
//        //
//    }
//
//
//    public function destroy($id)
//    {
//        //
//    }
}
