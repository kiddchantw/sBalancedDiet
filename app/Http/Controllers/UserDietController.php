<?php

namespace App\Http\Controllers;

use App\userDiet;
use Illuminate\Http\Request;

class UserDietController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        echo "UserDietController";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $newRecord = userDiet::create($request->all());
        if ($newRecord) {
            return response()->json(['success' => true, 'message' => "add success", 'data' => null], 200);
        } else {
            return response()->json(['success' => false, 'message' => "add error", 'data' => null], 400);
        }

        //todo:create or update有沒有更好的寫法
        //todo:validate

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\userDiet  $userDiet
     * @return \Illuminate\Http\Response
     */
    public function show(userDiet $userDiet)
    {
        //
        return response()->json(['success' => true , 'message' =>"" , 'data'=>$userDiet ],200);
    }


    public function showStandard(Request $request)
    {
        //show user standard diet
        $data = userDiet::where(
            'user_id','=', $request->user_id
        )->where('kind' ,'=', $request->kind)->get();
//            [']
            //,[]
//        )->get();

        return response()->json(['success' => true , 'message' =>"" , 'data'=>$data ],200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\userDiet  $userDiet
     * @return \Illuminate\Http\Response
     */
    public function edit(userDiet $userDiet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\userDiet  $userDiet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, userDiet $userDiet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\userDiet  $userDiet
     * @return \Illuminate\Http\Response
     */
    public function destroy(userDiet $userDiet)
    {
        //
    }
}
