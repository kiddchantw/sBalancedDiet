<?php

namespace App\Http\Controllers;

use App\userDiet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return userDiet::find(5);
        //show user standard diet
        $userId = $request->user_id;
        $standardKind = $request->kind;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
//        $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->timestamp ;
//        $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->timestamp ;
//        $startDate = Carbon::parse('2020-09-13')->timestamp;
//        $endDate = Carbon::parse('2020-09-14')->timestamp;
//
//        $startDate =  strtotime('2020-09-13');
//        $endDate =  strtotime('2020-09-14') ;

//        dd(Carbon::now());

//        $format = Carbon::parse('2020-09-13')->format('Y-m-d 00:00:00');
//        dd($format);
//        $startDate = (Carbon::createFromFormat('Y-m-d 00:00:00', '2020-09-13'));
//        $endDate = (Carbon::createFromFormat('Y-m-d 23:59:59', '2020-09-14'));

//
//        $startDate =  Carbon::parse('2020-09-12')->format('Y-m-d 00:00:00');
//        $endDate =  Carbon::parse('2020-09-12')->format('Y-m-d 23:59:59');


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
//                ->whereBetween('updated_at', [$startDate, $endDate])
                ->orderBy('updated_at', 'asc')
                ->when($startDate, function ($query, $s) {
                    return $query->where('updated_at', '>=', $s);
                })
                ->when($endDate, function ($query, $e) {
                    return $query->where('updated_at', '<=', $e);
                })->get();
//            ;
//            dd($data->toSql(), $data->getBindings());

//            if (isset($startDate) & isset($endDate)) {
//                $data = $data->where('created_at', '=',$startDate);

//                $data = $data->where('created_at', '=',Carbon::now());
//                $data = $data->where('updated_at', '>',$startDate)->where('updated_at', '<=',$endDate);
//                $data = $data->whereBetween('updated_at', [$startDate, $endDate]);


//                $data = $data->where('updated_at', '<', $endDate);


//                $data = $data->where('created_at', '>=',Carbon::createFromFormat('Y-m-d H:i:s', '2020-09-14 00:00'));
            // Carbon::now()->startOfWeek()
//            }
            //dd($data);
        }

        return response()->json(['success' => true, 'message' => "", 'data' => $data], 200);


    }


    public function showDietByDay(Request $request)
    {
        $data = userDiet::select([
            DB::raw('DATE(updated_at) as day'),
            DB::raw('sum(fruits) as A'),
            DB::raw('sum(vegetables) as B'),
            DB::raw('sum(grains) as c'),
            DB::raw('sum(nuts) as d'),
            DB::raw('sum(proteins) as e'),
            DB::raw('sum(dairy) as f'),

        ])->groupBy('day')
            ->where('user_id', '=', $request->user_id)
            ->whereNotIn('kind', [1])
            ->get();

        return response()->json(['success' => true, 'message' => "", 'data' => $data], 200);

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
        //
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
    }
}
