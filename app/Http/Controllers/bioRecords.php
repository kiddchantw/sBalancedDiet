<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bio;

class bioRecords extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //all user all bio record
        return response()->json(['message' => Bio::all()], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());

        if( isset($request->weight) )
        {
            $type = "1" ;
            $value = $request->weight ;

            $bioAdd = new Bio;
            $bioAdd->user_id = $request->user_id;
            $bioAdd->type = $type;
            $bioAdd->value = $value;
            //        dd($bioAdd);
            $bioAdd->save();

            if ($bioAdd == true) {
                return response()->json(['message' => 'weight add success'], 200);
            } else {
                return response()->json(['message' => 'weight add error'], 400);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = intval($id);
        return response()->json(['message' => Bio::where('id','=',$id)->get()
        ], 200);

    }

    public function showByUser($id)
    {
        $id = intval($id);
        return response()->json(['message' => Bio::where('user_id','=',$id)->get()
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        dd('update' );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        dd('delete' );

        $id = intval($id);
        $bioD =  Bio::where('id', '=', $id);

//        //return
        if ($bioD->exists()){
            $bioD->delete();
            return response()->json(['message' => 'delete bio record success'], 200);
        }else{
            return response()->json(['message' => 'delete $bioD record id error'], 400);
        }

    }
}
