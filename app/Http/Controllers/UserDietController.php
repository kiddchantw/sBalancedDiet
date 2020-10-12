<?php

namespace App\Http\Controllers;

use App\userDiet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserDietController extends Controller
{
    public static $dietColumn = ['fruits', 'vegetables', 'grains', 'nuts', 'proteins', 'dairy','water'];


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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public $messageValidate = [
        "user_id.required" => "請確認使用者",
        "user_id.exist" => "請確認使用者帳號",
        "kind.required" => "請確認紀錄種類",
        "diet_type.required" => "請確認用餐種類",

        "fruits.numeric"=> "請輸入水果數量",
        "vegetables.numeric"=> "請輸入蔬菜數量",
        "grains.numeric"=> "請輸入全穀雜糧類數量",
        "nuts.numeric"=> "請輸入堅果油脂數量",
        "proteins.numeric"=> "請輸入豆魚蛋肉類數量",
        "dairy.numeric"=> "請輸入乳製品數量",
        "water.numeric"=> "請輸入水量",


        "start_date.date_format" => "請確認日期格式",
        "end_date.date_format" => "請確認日期格式",

    ];


    public function customValidate(Request $request, array $rulesInput)
    {
        try {
            $this->validate($request, $rulesInput, $this->messageValidate);
        } catch (ValidationException $exception) {
            $errorMessage = $exception->validator->errors()->first();
//            $errorMessage = $exception->validator->getMessageBag();
            return $errorMessage;
        }
    }

    public function store(Request $request)
    {

        $rules = [
            "user_id" => "required ",
            "kind" => "required",
            "diet_type" => "required",
            "fruits" => "numeric",
            "vegetables" => "numeric",
            "grains" => "numeric",
            "nuts" => "numeric",
            "dairy" => "numeric",
            "water" => "numeric",
        ];
        $validResult = $this->customValidate($request, $rules);
        if ($validResult != Null) {
            return response()->json(['success' => false, 'message' =>$validResult , 'data'=> null ],400);
        }

        //todo:create or update有沒有更好的寫法
        $newRecord = userDiet::create($request->all());
        if ($newRecord) {
            return response()->json(['success' => true, 'message' => "add success", 'data' => null], 200);
        } else {
            return response()->json(['success' => false, 'message' => "add error", 'data' => null], 400);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param \App\userDiet $userDiet
     * @return \Illuminate\Http\Response
     */
    public function show(userDiet $userDiet)
    {
        //
        return response()->json(['success' => true, 'message' => "", 'data' => $userDiet], 200);
    }


    public function showDiet(Request $request)
    {

        $rules = [
            "user_id" => "required ",
            "kind" => "required",
            "start_date" => "nullable | date_format:Y-m-d",
            "end_date" => "nullable | date_format:Y-m-d"
        ];
        $validResult = $this->customValidate($request, $rules);
        if ($validResult != Null) {
            return response()->json(['success' => false, 'message' =>$validResult , 'data'=> null ],400);
        }



        $userId = $request->user_id;
        $standardKind = $request->kind;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($standardKind != 0) {
            $data = userDiet::select(
                DB::raw('kind'),
                DB::raw('fruits'),
                DB::raw('vegetables'),
                DB::raw('grains'),
                DB::raw('nuts'),
                DB::raw('proteins'),
                DB::raw('dairy'),
                DB::raw('created_at')
            )->where([['user_id', '=', $userId], ['kind', '=', $standardKind]])->first();
        } else {

            if (isset($startDate) & isset($endDate)) {
                $startDate = Carbon::parse($startDate . ' 00:00:00')->format('Y-m-d H:i:s');
                $endDate = Carbon::parse($endDate . ' 23:59:59')->format('Y-m-d H:i:s');
            }

            $data = userDiet::query()
                ->where([['user_id', '=', $userId], ['kind', '=', $standardKind]])
                ->orderBy('updated_at', 'asc')
                ->when($startDate, function ($query, $s) {
                    return $query->where('updated_at', '>=', $s);
                })
                ->when($endDate, function ($query, $e) {
                    return $query->where('updated_at', '<=', $e);
                })
                ->get();

        }

        return response()->json(['success' => true, 'message' => "", 'data' => $data], 200);


    }


    public function showDietByDay(Request $request)
    {
        $rules = [
            "user_id" => "required ",
            "start_date" => "nullable | date_format:Y-m-d",
            "end_date" => "nullable |  date_format:Y-m-d"
        ];
        $validResult = $this->customValidate($request, $rules);
        if ($validResult != Null) {
            return response()->json(['success' => false, 'message' =>$validResult , 'data'=> null ],400);
        }


        $userId = $request->user_id;
//
        $dataUserStandard = userDiet::where([['user_id', '=',$userId], ['kind', '=', 1]])->get();
//        dd();
        if ($dataUserStandard->count() == 0)
        {
            return response()->json(['success' => false, 'message' =>"user diet standard error" , 'data'=> null ],400);
        }

        $arr1 = array();
        foreach ($dataUserStandard as $userDiet) {
            $arr1 = array(
                $userDiet->fruits,
                $userDiet->vegetables,
                $userDiet->grains,
                $userDiet->nuts,
                $userDiet->proteins,
                $userDiet->dairy
            );
        }


        $dateS = $request->start_date;
        $dateE = $request->end_date;

        $dataOneDay = userDiet::select([
            DB::raw('DATE(updated_at) as day'),
            DB::raw('sum(fruits) as A'),
            DB::raw('sum(vegetables) as B'),
            DB::raw('sum(grains) as c'),
            DB::raw('sum(nuts) as d'),
            DB::raw('sum(proteins) as e'),
            DB::raw('sum(dairy) as f'),
        ])->groupBy('day')
            ->where('user_id', '=', $request->user_id)
            ->where('kind', '=', 0)
            ->get()
//            ->where('day', '=',$dateS )
            ->whereBetween('day', [$dateS, $dateE])->values();

        $dayCount = $dataOneDay->count();
        $result = array();
        for ($j = 0; $j < $dayCount; $j++) {
            $cQuery = $dataOneDay->get($j);
            $loopDay = "";
            foreach ($cQuery as $userDiet) {
                $loopDay = $cQuery->day;
                $arrDay = array(
                    $cQuery->A,
                    $cQuery->B,
                    $cQuery->c,
                    $cQuery->d,
                    $cQuery->e,
                    $cQuery->f
                );
            }

            $responseloop = array();
            $responseloop['date'] = $loopDay;
//
            $response = array();
            if (count($arr1) == count($arrDay)) {
                for ($i = 0; $i < count($arr1); $i++) {
                    $response[self::$dietColumn[$i]] = round(($arr1[$i] - $arrDay[$i]), 1);
                }
            }
            $responseloop['deficiency'] = $response;
            array_push($result, $responseloop);


        }

        return response()->json(['success' => true, 'message' => "", 'data' => $result], 200);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\userDiet $userDiet
     * @return \Illuminate\Http\Response
     */
    public function edit(userDiet $userDiet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\userDiet $userDiet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, userDiet $userDiet)
    {
        //TODO:要設計非當日時間不可修改
        // if nowtime day > userDiet->updated_at

        foreach (self::$dietColumn as $value) {
            if ($request->filled($value)) {
                $userDiet->$value = $request->$value;
            }
            $userDiet->save();
        }
        if ($userDiet == true) {
            return response()->json(['success' => true, 'message' => "update success", 'data' => null], 200);
        } else {
            return response()->json(['success' => false, 'message' => "update  error", 'data' => null], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\userDiet $userDiet
     * @return \Illuminate\Http\Response
     */
    public function destroy(userDiet $userDiet)
    {
        //
        $userDiet->delete();
        return response()->json(['success' => true, 'message' => "delete success", 'data' => null], 200);
    }
}
