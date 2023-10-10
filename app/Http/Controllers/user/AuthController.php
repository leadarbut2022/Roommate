<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\commentsUsers;
use App\Models\CommintsUsers;
use App\Models\Rating;
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
        $req_is_Set = UserValdateInformation::where('user_id',$id);
        if ($req_is_Set ) {
            return Common::apiResponse(1,'you olready have request',null,403);
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
    public function User_data(Request $request)
    {
        $user_id =Auth::user()->id;
        if (isset($request->user_id)) {
            $user_id =$request->user_id;
        }

        $data= User::where('id',$user_id)->with('comments','posts')->first();
        $averageRating = $this->getAverageRatingForPost($data);
        $data['user_rate'] =$averageRating;
        unset($data['rate']);

        return $data;
        
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
        $user = User::where('email',$request->email)->first();
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
    public function rate(Request $request, user $user)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);
        if($user->id == Auth::user()->id)return Common::apiResponse (0, 'You can`t  rate Your self.',null,403); 
    
    
        $existingRating = Rating::where('user_id', auth()->id())->where('user', $user->id)->first();
    
        if ($existingRating) {
            Rating::where('user_id',auth()->id())->where('user', $user->id)->update([
                'rating' => $request->input('rating'),
            ]);
            return Common::apiResponse (1, 'Now You edit the rate for this user.',null,200);
        }
    
        // Create a new rating record
        Rating::create([
            'user_id' => auth()->id(),
            'user' => $user->id,
            'rating' => $request->input('rating'),
        ]);
    
         return Common::apiResponse (true, 'Thank you for rating this user.',null,200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function comment(Request $request,User $user)
    {
        $request->validate([
            'comment' => 'required',
        ]);
    
        if($user->id == Auth::user()->id)return Common::apiResponse (0, 'You can`t  comment Your self.',null,403); 

        // $existingRating = CommintsUsers::where('user_id', auth()->id())->where('user', $user->id)->first();
    
        // if ($existingRating) {
            
        //     return Common::apiResponse (0, 'You have already commented this user.',null,403);
        // }
    
        // Create a new rating record
        CommintsUsers::create([
            'user_id' => auth()->id(),
            'user' => $user->id,
            'commint' => $request->input('comment'),
        ]);
    
         return Common::apiResponse (true, 'Thank you for commented this user.',null,200);
    }

    /**
     * Update the specified resource in storage.
     */
    function getAverageRatingForPost(user $user)
    {
        $averageRating = $user->rate->avg('rating');
    
        return $averageRating ?: 0;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function edit_commint(Request $request,User $user)
    {
        CommintsUsers::where('user_id',auth()->id())->where('user', $user->id)->update([
            'commint' => $request->input('comment'),
        ]);
        return Common::apiResponse (1, 'Now You edit the rate for this user.',null,200);
    }


    public function delete_commint(Request $request,CommintsUsers $comment)
    {
       $delete = CommintsUsers::where('id', $comment->id);
       if (!$delete) {
           return Common::apiResponse (0, 'try leter',null,500);
       }
       return Common::apiResponse (1, 'Deleted.',null,200);

    }

    public function edit_commint2(Request $Request)
    {
        
    }
}
