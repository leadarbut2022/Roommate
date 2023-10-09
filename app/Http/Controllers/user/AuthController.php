<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\User;
use App\Models\UserValdateInformation;
use HamRequestest\Core\HasToString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function valdate(Request $request)
    {
        $id =Auth::user()->id;
        $user = User::find($id);
        if (!$user ) {
            return Common::apiResponse(0,'Requestedentials does\'t match',null, 503);
        }

         	 
        $ni_photo= '';
        $ni2_photo='';
         
        if (isset($request->ni_photo)) {
            $ni_photo= Common::upload('valdate/' , $request->ni_photo);

        }
        if (isset($request->ni2_photo)) {
            $ni2_photo= Common::upload('valdate/' , $request->ni2_photo);

        }

   
        $add=UserValdateInformation::create([
                'user_id'=>$id,
                'ni_photo'=>$ni_photo,
                'ni2_photo'=>$ni2_photo
             ]);

        if (!$add) {
            return Common::apiResponse(0, 'Failed to valdate user', null, 500); // Changed the status code to 500 for server error
        }     
            
        return Common::apiResponse(1, 'User valdate successfully',null, 200); // Changed the status code to 201 for resource Requesteation


    
    }

    /**
     * Show the form for Requesteating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $data=$request->all();
    //     $data['password']=Hash::make($request->password);
    //     // dd($data['phone_number']);
    //     $user = User::create([
    //         'email' => $data['email'],
    //         'phone_number' => $data['phone_number'],
    //         'name' => $data['name'],
    //         'password' => $data['password'],
    //     ]);

    //     if (!$user) {
    //         return Common::apiResponse(0,'try_again','',403);
    //     }
    //     $token = $user->createToken('api_token')->plainTextToken;
    //     $user->auth_token=$token;
    //     return Common::apiResponse(0,'success',$token,200);
    // }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20', // You can adjust the maximum length
            'password' => 'required|string|min:8', // You can adjust the minimum length
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create([
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'name' => $data['name'],
            'password' => $data['password'],
        ]);

        if (!$user) {
            return Common::apiResponse(0, 'Failed to create user', null, 500); // Changed the status code to 500 for server error
        }

        $token = $user->createToken('api_token')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        return Common::apiResponse(1, 'User created successfully', ['token' => $token], 200); // Changed the status code to 201 for resource Requesteation
    }

    public function login(Request $request){
        $user = User::where('phone_number',$request->phone_number)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return Common::apiResponse(0,'Requestedentials does\'t match',null, 503);
        }
        $token = $user->createToken('api_token')->plainTextToken;
        $user->remember_token=$token;
        // if (!$this->canLogin($user)){
        //     return Common::apiResponse (false,'you are blocked',[],408);
        // }
        return Common::apiResponse (true,'logged in successfully',$user,200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $Request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $Request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $Request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $Request)
    {
        //
    }
}
