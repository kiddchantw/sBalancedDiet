<?php

namespace App\Http\Controllers;

use App\userDiet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserDietController extends Controller
{
    public static $dietColumn = ['fruits', 'vegetables', 'grains', 'nuts', 'proteins', 'dairy'];


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
        //show user standard diet
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
        $userId = $request->user_id;

        $dataS = userDiet::where([['user_id', '=', $userId], ['kind', '=', 1]])->get();

        $arr1 = array();
        foreach ($dataS as $userDiet) {
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
            ->whereBetween('day', [$dateS, $dateE]);

        $dayCount = $dataOneDay->count();

        $result = array();
        for ($j = 0; $j < $dayCount; $j++) {
            $cQuery = $dataOneDay->get($j);
            var_dump($cQuery);
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


//        var_dump( $response );


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

        foreach (self::$dietColumn as $value) {
            if ($request->filled($value)) {
//                    echo $value." : ".$request->$value;
//                    echo "<br>";
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
