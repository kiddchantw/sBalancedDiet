<?php

namespace App\Http\Controllers;

use App\User;
use App\userWater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserWaterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        echo "UserWaterController";
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//
//        $newRecord = userWater::create(
//            ['user_id' => $request->user_id],
//            ['water' => $request->water]
//        );

        $newRecord = userWater::create(
            ['user_id' => $request->user_id, 'water' => $request->water]
        );

        if ($newRecord) {
            return response()->json(['success' => true, 'message' => "add success", 'data' => null], 200);
        } else {
            return response()->json(['success' => false, 'message' => "add error", 'data' => null], 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\userWater $userWater
     * @return \Illuminate\Http\Response
     */
    public function show(userWater $userWater)
    {
        //
    }

    public function showOneDay(Request $request)
    {


        $data = userWater::select([
            // This aggregates the data and makes available a 'count' attribute
            DB::raw('sum(water) as `sum`'),
            // This throws away the timestamp portion of the date
            DB::raw('DATE(created_at) as day')
            // Group these records according to that day
        ])->groupBy('day')
            // And restrict these results to only those created in the last week
            ->where('user_id', '=', $request->user_id)
            ->first();  //最新的
//            ->get(); //所有的

        return response()->json(['success' => true, 'message' => "", 'data' => $data ], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\userWater $userWater
     * @return \Illuminate\Http\Response
     */
    public function edit(userWater $userWater)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\userWater $userWater
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, userWater $userWater)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\userWater $userWater
     * @return \Illuminate\Http\Response
     */
    public function destroy(userWater $userWater)
    {
        //
    }
}
