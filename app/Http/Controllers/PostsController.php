<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Common;
use App\Models\posts;
use App\Models\PostsImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) 
    {
        $data = new posts();

        if ($request->has('gov')) {
            $data = $data->where('governorate', $request->gov);
        }
        if ($request->has('price')) {
            $price = $request->price;
            $data = $data->where('price', '<=', $price); 
        }
        if ($request->has('type')) {
            $data = $data->where('type', $request->type);
        }
        $result = $data->get();
        
        return $result;
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=request()->all();
        $data['user_id']=  Auth::user()->id;
        unset($data['imgs']);
        // dd($data);
        $create= posts::create($data);
        if (!$create) {
            return Common::apiResponse(0, 'Failed to Requesteate user', null, 500); // Changed the status code to 500 for server error
        }
        
        $images=request()->imgs;
        foreach ($images as $img){
        //  $path= Common::upload('images/' , $img);
         PostsImages::create([
            'post_id'=>$create->id,
            'img'=>$img
         ]);
        }
        return Common::apiResponse (true,'successfully',null,200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
