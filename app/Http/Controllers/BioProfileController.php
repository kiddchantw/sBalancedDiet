<?php

namespace App\Http\Controllers;

use App\bioProfile;
use Illuminate\Http\Request;

class BioProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

//    public function customResponse()
//    (Request $request, array $rulesInput)
//    {
//        try {
//            $this->validate($request, $rulesInput, $this->messageValidate);
//        } catch (ValidationException $exception) {
////            $errorMessage = $exception->validator->errors()->first();
//            $errorMessage = $exception->validator->getMessageBag();
//
//            return $errorMessage;
//        }
//        $response = array(
//            "remember_token" => $userL->remember_token,
//            "token_expire_time" => $userL->token_expire_time,
//            "user_id" => $userL->id
//        );
//
//    } else {
//$response = "login error";
//}
//
//return response()->json(['message' => $response], 200);

//        return response()->json([ 'status' => "true" , 'message' => "123" , 'data' => bioProfile::all()], 200);

//    }


    public function index()
    {
        //
//        return response()->json(['message' => bioProfile::all()], 200);
//        return response()->json(['name' => 'Abigail', 'state' => 123,'data'=>bioProfile::all()],202);
        return response()->json(['success' => true , 'message' => "" , 'data'=>bioProfile::all()],200);
//        return response()->json(['success' => false, 'message' =>"reasonABC" , 'data'=> null ],400);

//        $this->customResponse();
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
        //
//        $userWeight =  $request->weight;
        $userWeight = (isset($request->weight)) ? $request->weight: 0;
        $userSystolic = (isset($request->systolic)) ? $request->systolic: 0;
        $userDiastolic =  (isset($request->diastolic)) ? $request->diastolic: 0;

//        dd($userWeight);

        if  ( $userWeight == 0 ) {
            //&& ($userSystolic==0)  &&  ($userDiastolic==0 ) ) {
            return response()->json(['success' => false, 'message' =>"請輸入體重 " , 'data'=> null ],400);
        }

        $bioAdd = new bioProfile();
        $bioAdd->user_id = $request->user_id ;
        $bioAdd->weight = $userWeight ;
        $bioAdd->systolic = $userSystolic ;
        $bioAdd->diastolic  = $userDiastolic ;
        $bioAdd->save();

        if ($bioAdd == true) {
            //return response()->json(['message' => 'weight add success'], 200);
            return response()->json(['success' => true , 'message' =>"add success" , 'data'=>null ],200);
        } else {
            //return response()->json(['message' => 'weight add error'], 400);
            return response()->json(['success' => false, 'message' =>"add error" , 'data'=> null ],400);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\bioProfile  $bioProfile
     * @return \Illuminate\Http\Response
     */
    public function show(bioProfile $bioProfile)
    {
        return response()->json(['success' => true , 'message' =>"" , 'data'=>$bioProfile ],200);

//        dd($id);
        //        $updateTaskId = $bioProfile->id;
//        $updateTask = Tasks::where('id', '=', $updateTaskId);
//        return response()->json(['message' => $bioProfile], 200);
//        if ($bioProfile->exists()){
//        }else{
//        }

    }

    public function showByUser($id)
    {
        $id = intval($id);
        $userP = bioProfile::where('user_id','=',$id)->get();
        if ( $userP ){
            return response()->json(['success' => true , 'message' =>"" , 'data'=>$userP ],200);
        } else{
            return response()->json(['success' => false, 'message' =>"user id error" , 'data'=> null ],400);
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\bioProfile  $bioProfile
     * @return \Illuminate\Http\Response
     */
    public function edit(bioProfile $bioProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\bioProfile  $bioProfile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, bioProfile $bioProfile)
    {
//        dd("update");
//        dd($request->all());

        if (isset($request->weight) ) {
            $bioProfile->weight =  $request->weight ;
        }

        if (isset($request->systolic) ) {
            $bioProfile->systolic =  $request->systolic ;
        }

        if (isset($request->diastolic) ) {
            $bioProfile->diastolic =  $request->diastolic ;
        }
        $bioProfile->save();

        if ($bioProfile == true) {
//            return response()->json(['message' => 'weight add success'], 200);
            return response()->json(['success' => true , 'message' =>"update success" , 'data'=>null ],200);
        } else {
//            return response()->json(['message' => 'weight add error'], 400);
            return response()->json(['success' => false, 'message' =>"update  error" , 'data'=> null ],400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\bioProfile  $bioProfile
     * @return \Illuminate\Http\Response
     */
    public function destroy(bioProfile $bioProfile)
    {
        $bioProfile->delete();
        return response()->json(['success' => true , 'message' =>"delete success" , 'data'=>null ],200);

    }

}
