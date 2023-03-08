<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;

class UserController extends Controller
{
   public function register(Request $request){
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email',
      'password' => [
            'required',
            'min:6',
            'max:12',
            'confirmed'
        ]
       
    ]);
    if($validator->fails()){
        return $this->sendError('Validation Error.', $validator->errors());       
    }
    $input = $request->all();
    
    $input['password'] = Hash::make($input['password']);
    $user = User::create($input);
    $token=$user->createToken('forRegistration')->accessToken;
    return response()->json([
            'token'=>$token,
            'user'=>$user,
            'message'=>'user created successfully',
           
    ]);
   
    
    
   }
   public function login(Request $request){


    $userlogin = User::where('email', $request->email)->first();

    if($userlogin && hash::check($request->password, $userlogin->password)){
        $token =  $userlogin->createToken('forLogin')->accessToken;
        return response()->json([
            'token'=>$token,
            'user'=>$userlogin,
            'message'=>'user login successfully',
        ]);
    }
 }
        public function getUser($id){
            
            $user= User::find($id);
            if(empty($user)){
                return response()->json([
                   
                    'user'=>'Null',
                    'message'=>'user not Found ',
                ]);
            }else{
                return response()->json([
                    
                    'user'=>$user,
                    'message'=>'user found',
                ]);

            }
        }
        public function getUsers(){
            
            $user= User::all();
            if(empty($user)){
                return response()->json([
                   
                    'user'=>'Null',
                    'message'=>'user not Found ',
                ]);
            }else{
                return response()->json([
                    
                    'user'=>$user,
                    'message'=>'user found',
                ]);

            }
           
        }

        public function userUpdate(Request $request,$id){
            
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
              'password' => 
                    'required',
          ]);
            
            $input = $request->all();
            if(!empty($input['password'])){ 
                $input['password'] = Hash::make($input['password']);
            }else{
                $input = Arr::except($input,array('password'));    
            }
            $user = User::find($id);
            
          $updated= $user->update($input);
          
        }
        public function deleteUser($id){
            dd('hello');
                $user= User::find($id);
                if(empty($user)){
                    return response()->json([
                       
                        'user'=>'Null',
                        'message'=>'user not Found ',
                    ]);
                }else{
                    DB::transaction();
                      try {
                    $user->delete();
                   DB::commit();
                    return response()->json([
                        
                        'user'=>$user,
                        'message'=>'user delete successfully',
                    ]);
                } catch(\Exception $e){
                    DB::rollBack();
                }

                    
    
                }
        }

   }

