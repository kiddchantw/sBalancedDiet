<?php

namespace App\Http\Controllers;

use App\User;
use App\userWater;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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


    public $messageValidate = [
        "water.integer"=> "請輸入正整數",
        "water.required" => "請輸入飲水量",
        "user_id.required" => "請輸入user id",
    ];

    public function customValidate(Request $request, array $rulesInput)
    {
        try {
            $this->validate($request, $rulesInput, $this->messageValidate);
        } catch (ValidationException $exception) {
            $errorMessage = $exception->validator->errors()->first();
            return $errorMessage;
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "user_id" => "required|string | regex:/^[0-9]+$/",
            "water" => "required | integer "
        ];
        $validResult = $this->customValidate($request, $rules);
        if (!is_null($validResult)){
            return response()->json(['success' => false, 'message' =>$validResult , 'data'=> null ],400);
        }

        $newRecord = userWater::create([
                'user_id' => $request->user_id,
                'water' => $request->water
            ]);

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
        dd($userWater);
    }


    public function waterUser($id)
    {
        $id = intval($id);
        $userP = userWater::where('user_id','=',$id)->get();
        if ( $userP ){
            return response()->json(['success' => true , 'message' =>"" , 'data'=>$userP ],200);
        } else{
            return response()->json(['success' => false, 'message' =>"user id error" , 'data'=> null ],400);
        }
    }



    public function waterDay(Request $request)
    {
        $data = userWater::select([
            DB::raw('sum(water) as sum'),
            DB::raw('DATE(created_at) as day')
        ])->groupBy('day')
            ->where('user_id', '=', $request->user_id )
            ->get();



        $startDate =  $request->start_date;
        $endDate =  $request->end_date;


        if (isset($startDate) & isset($endDate)){
            //如果有輸入日期，就只提供該日的
            try {
                $rules = [
                    "user_id" => "required",
                    "start_date" => "date_format:Y-m-d",
                    "end_date" => "date_format:Y-m-d"
                ];
                $message = [
                    "user_id" => "請確認使用者",
                    "start_date.date_format" => "請確認日期格式",
                    "end_date.date_format" => "請確認日期格式",
                ];
                $request->validate($rules, $message);
            } catch (ValidationException $exception) {
                $errorMessage = $exception->validator->errors()->first();
                return response()->json(['success' => false, 'message' =>$errorMessage , 'data'=> null ],400);
            }

//單一日ok
//            $data = $data->where('day','=',$request->day);
            $data = $data->whereBetween('day', [ $startDate, $endDate ] );

        }
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
        $rules = [
            "water" => "required | integer"
        ];
        $validResult = $this->customValidate($request, $rules);

        if (!is_null($validResult)){
            return response()->json(['success' => false, 'message' =>$validResult , 'data'=> null ],400);
        }
        $userWater->water =  $request->water;
        $userWater->save();

        if ($userWater == true) {
            return response()->json(['success' => true , 'message' =>"update success" , 'data'=>null ],200);
        } else {
            return response()->json(['success' => false, 'message' =>"update  error" , 'data'=> null ],400);
        }

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
        $userWater->delete();
        return response()->json(['success' => true , 'message' =>"delete success" , 'data'=>null ],200);
    }
}
