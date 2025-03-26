<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function createUser(Request $request){

        $validator=Validator::make($request->all(),[
            'name'=>"required|string",
            'email'=>"required|string|unique:users",
            'phone'=>"required|numeric",
            'password'=>"required|min:6"
        ]);

        if($validator->fails()){
            $result=array('status'=>false,'message'=>"validation error occured",'error_message'=>$validator->errors());
            return response()->json($result,400); //bad request
        }
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password'=>  bcrypt ($request->password),
           
        ]);

        if($user->id){
            $result=array('status'=>true,'message'=>"User created","data"=>$user);
            $responseCode=200; //sucess request
        }
        else{
            $result=array('status'=>false,'message'=>"something went wrong");
            $responseCode=400;  //fucked request
        }
        return response()->json($result,$responseCode);
    
    }





    public function getUsers(){

       
        $users=User::all();
        $result=array('status'=>true,'message'=>count($users)."user(s) fetched","data"=>$users);
        $responseCode=200;
        return response()->json($result,$responseCode);
    
    }


    public function getUserById($id){

        $user=User::find($id);
        if(!user::find($id)){
            return response()->json(['status'=>false,'message'=>"User not found",400]);
        }
        $result=array('status'=>true,'message'=>"user found","data"=>$user);
        $responseCode=200;
        return response()->json($result,$responseCode);
    }




    public function updateUser(Request $request, $id) {
        // Find user
        $user = User::find($id);
        if (!$user) {
            return response()->json(['status' => false, 'message' => "User not found"], 404);
        }
    
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => "required|string",
            'email' => "required|string|email|unique:users,email".$id,
            'phone' => "required|numeric",
            'password' => "nullable|min:6"  // Password is optional
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Validation error occurred",
                'error_message' => $validator->errors()
            ], 400);
        }
    
        // Update user fields
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        
        // Update password only if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        $user->save();
    
        return response()->json([
            'status' => true,
            'message' => "User updated successfully",
            'data' => $user
        ], 200);
    }



    public function deleteUser($id){

        $user=User::find($id);
        if(!user::find($id)){
            return response()->json(['status'=>false,'message'=>"User not found",400]);
        }
        $user->delete();
        $result=array('status'=>true,'message'=>"user has been deleted sucessfully");
        $responseCode=200;
        return response()->json($result,$responseCode);
    }


}
