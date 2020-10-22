<?php

namespace App\Http\Controllers;

use App\bioProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BioProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    public function index()
    {
        return response()->json(['success' => true , 'message' => "" , 'data'=>bioProfile::all()],200);
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

    //API_11_bioProfile_add
    public function store(Request $request)
    {
        $rules11 = [
            "weight" => "required | numeric  | regex:/^[0-9]+(\.[0-9]??)?$/ |  between:0,250.0",
        ];
        $validResult = $this->customValidate($request, $rules11);
        if ($validResult != Null) {
            return response()->json(['success' => false, 'message' => $validResult, 'data' => null], 400);
        }


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
    }

    //API_12_bio_showUserBio
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


    //API_016_showWeight
    public function showWeight(Request $request)
    {
//        $request->user()->id;
        $dateStart = $request->start_date;
        $dateEnd = $request->end_date;

//        $now = strtotime($dateEnd); // or your date as well
//        $your_date = strtotime("$dateStart");
//        $datediff = $now - $your_date;
//
//        echo round($datediff / (60 * 60 * 24));


//        $earlier = new DateTime("2010-07-06");
//        $later = new DateTime("2010-07-09");
//
//        $diff = $later->diff($earlier)->format("%a");

        $datetime1 = date_create($dateStart);
        $datetime2 = date_create($dateEnd);
        $interval = date_diff($datetime1, $datetime2) ->d ;

//        dd(  $interval );

        for ($x = 0; $x <= $interval ; $x++) {
//            $toA = Carbon::createFromFormat('Y-m-d', $dateStart)->addDays($x);
//            $toA = $datetime1->addDays($x);
            $toA = date('Y-m-d', strtotime($dateStart. ' + '.$x .'days'));
//            $newDate = strtotime($myDate . '+ '.$nDays.'days');

            echo "The $x day is:  $toA";
            echo '<br>';
        }

//        $diff = round(strtotime($dateEnd) - strtotime($dateStart));
////        $interval = $d1->diff($d2);
////        $diffInDays  = $diff->d; //21
//
//
//
//        dd($diff/60/60/24);


//
//        return bioProfile::where('user_id','=',$request->user()->id)
//            ->whereBetween('created_at', [$dateStart." 00:00:00",$dateEnd." 23:59:59"])
//            ->get();


        //error
//        $testB = bioProfile::where('user_id','=',$request->user()->id)
//            ->whereRaw('created_at + interval 1 day >= ?', [$dateStart])
//            ->whereRaw('created_at + interval 1 day < ?', [$dateEnd])
//            ->get();
//
//        return $testB ;


//        foreach (Carbon::range($dateStart, $dateEnd) as $dateT ) {
//            echo $dateT ;
//        }


//        $date = date_create('2000-01-01');
//        date_add($date, date_interval_create_from_date_string('10 days'));
//        echo date_format($date, 'Y-m-d');

//        echo Carbon::now()->addDays(1);

//        $days = $dateEnd->diffInDays($dateStart);

//        $days=date_diff($startDate,$endDate);
//        var_dump($days);
//
//        $to = Carbon::createFromFormat('Y-m-d H:s:i', $dateStart." 00:00:00");
//        $from = Carbon::createFromFormat('Y-m-d H:s:i', $dateEnd." 23:59:59");
//
//
//
//        $diff_in_days = $to->diffInDays($from);
//
//        print_r($diff_in_days); // Output:
//        echo '<br>';
//
//        for ($x = 0; $x < $diff_in_days ; $x++) {
//            $toA = Carbon::createFromFormat('Y-m-d', $dateStart)->addDays($x);
////            $toA = $dateStart->addDays($x);
//            echo "The $x day is:  $toA";
//            echo '<br>';
//        }
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
    //Api_15_bio_updat
    public function update(Request $request, bioProfile $bioProfile)
    {
        $rules15 = [
            "weight" => "required | numeric  | regex:/^[0-9]+(\.[0-9]??)?$/ |  between:0,250.0",
        ];
        $validResult = $this->customValidate($request, $rules15);
        if ($validResult != Null) {
            return response()->json(['success' => false, 'message' => $validResult, 'data' => null], 400);
        }



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


    public function customValidate(Request $request, array $rulesInput)
    {
        try {
            $this->validate($request, $rulesInput, $this->messageValidate);
        } catch (ValidationException $exception) {
            $errorMessage = $exception->validator->errors()->first();
            return $errorMessage;
        }
    }

    public $messageValidate = [
        "weight.required" =>"請輸入體重",
        "weight.between" => "請輸入數字0~300.0",
        "weight.regex" => "小數點後1位"
    ];

}
