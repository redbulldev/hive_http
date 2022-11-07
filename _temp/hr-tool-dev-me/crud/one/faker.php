<?php

use Illuminate\Database\Capsule\Manager as DB;
//sendMessage('namng','DEMO Tin nhắn');
//die();
// $cvs = DB::table('cv')->where('step','>','3')->whereNotExists(function($query)
//             {
//                 $query->select(DB::raw(1))->from('to_interview')->whereRaw('to_interview.cv_id = cv.id');
//             })
//             ->limit(100)->get();
// foreach($cvs as $cv)
// {
//     DB::table('to_interview')->insert([
//         'cv_id' =>$cv->id,
//         'author_id' =>$cv->author_id,
//         'appoint_type'=>$cv->appoint_type,
//         'appoint_date'=>!empty($cv->appoint_date)?$cv->appoint_date:$cv->datecreate,
//         'appoint_place'=>$cv->appoint_place,
//         'appoint_link'=> !empty($cv->appoint_link)?$cv->appoint_link:'',
//         'interviewer_id' =>$cv->interviewer_id,
//         'status' => 2,
//         'datecreate'=> $cv->datecreate,
//         'datemodified'=> $cv->datecreate,
//     ]);
//     echo '<pre>';  
//     print_r($cv);
//     echo '</pre>';
// }
// die();
// $faker = Faker\Factory::create('vi_VN');
// //Tạo dữ liệu lịch sử CV giả
// for ($i=0; $i <10 ; $i++) { 
//     //DB::table('cv')->where('status','5')->orderByRaw('RAND()')->limit($faker->numberBetween(20,50))->update(['status'=>$i]);
// }
// $interview_cv=3;
// $pass_cv=4;
// $offer_cv=5;
// $offer_success=6;
// $onboard_cv=7;
// try {
// //->where('total_cv',0)
// $requests = DB::table('request')->where('total_cv',0)->limit(100)->get();
// foreach($requests as $log)
// {
//     $emp = DB::table('cv')->where('request_id',$log->id)->where('status','>=',$onboard_cv)->selectRaw('GROUP_CONCAT(fullname) as title')->first();
//     if($emp){
//         DB::table('request')->where('id',$log->id)->update([
//             'total_cv'=>DB::table('cv')->where('request_id',$log->id)->count(),
//             'interview_cv'=>DB::table('cv')->where('request_id',$log->id)->where('status','>=',$interview_cv)->count(),
//             'pass_cv'=>DB::table('cv')->where('request_id',$log->id)->where('status','>=',$pass_cv)->count(),
//             'offer_cv'=>DB::table('cv')->where('request_id',$log->id)->where('status','>=',$offer_cv)->count(),
//             'offer_success'=>DB::table('cv')->where('request_id',$log->id)->where('status','>=',$offer_success)->count(),
//             'onboard_cv'=>DB::table('cv')->where('request_id',$log->id)->where('status','>=',$onboard_cv)->count(),
//             'employees'=>json_encode(explode(',',$emp->title))
//         ]);
//         echo '<pre>';
//         print_r($emp);
//         echo '</pre>';
//     }
// }
// DB::table('request')->where('onboard_cv',0)->update(['employees'=>null]);
// } catch (Exception $e) {
//    print_r($e);
//    die();
// }

/*
$total=50;
for($i=1;$i<$total;$i++)
{
    $request= DB::table('request')->whereNotIn('id', function($query) { $query->select('request_id')->from('request_logs'); })->first();
    if($request)
    {
        $author = DB::table('users')->orderByRaw('RAND()')->first();
        $source = DB::table('source')->orderByRaw('RAND()')->first();
        $date=$faker->dateTimeBetween($startDate = '-40 years', $startDate = '-20 years');
        $total_cv=$faker->numberBetween(10,40);
        for($j=1;$j<$total_cv;$j++)
        {
            $addcv=[
                'request_id'=>$request->id,
                'author_id'=>$author->username,
                'interviewer_id'=>$request->requestor_id,
                'reviewer_id'=>$request->decision_id,
                'position_id'=>$request->position_id,
                'level_id'=>$request->level_id,
                'source_id'=>$source->id,
                'fullname'=>$faker->lastname().' '.$faker->firstname(),
                'email'=>$faker->email(),
                'mobile'=>$faker->phoneNumber(),
                'birthday'=>$date->format('Y-m-d'),
                'address'=>$faker->address(),
                'gender'=>$faker->numberBetween(0,1),
                'linkcv'=>$faker->imageUrl($width = 640, $height = 480),
                'images'=>json_encode([$faker->imageUrl($width = 640, $height = 480),$faker->imageUrl($width = 640, $height = 480),$faker->imageUrl($width = 640, $height = 480)]),
                'salary'=>$faker->numberBetween(1000,2000)*23000,
                'datecreate'=>time(),
                'datemodified'=>time(),
                'status'=>0
            ];
            DB::table('cv')->insert($addcv);
            echo '<pre>';
            print_r($addcv);
            echo '</pre>';
        }
        $addlog=[
           'author_id' =>$request->author_id,
           'request_id' =>$request->id,
           'total_cv' =>$total_cv,
           'interview_cv' =>0,
           'pass_cv' =>0,
           'offer_cv' =>0,
           'offer_success' =>0,
           'onboard_cv' =>0,
           'datecreate'=>time(),
            'datemodified'=>time(),
        ];
        DB::table('request_logs')->insert($addlog);
        echo '<pre>';
        print_r($addlog);
        echo '</pre>';
    }
}

//Tạo dữ liệu Request giả
$year=2022;
for($month=1;$month<5;$month++)
{
    $requesters = DB::table('positions_requester')->get();
    foreach($requesters as $requester)
    {
        $decision = DB::table('users')->where('username','!=',$requester->user_id)->orderByRaw('RAND()')->first();
        $levels = DB::table('level')->orderByRaw('RAND()')->limit(2)->get();
        foreach($levels as $level)
        {
            $add=[
                'author_id'=>$requester->user_id,
                'requestor_id'=>$requester->user_id,
                'decision_id'=>$decision->username,
                'position_id'=>$requester->position_id,
                'level_id'=>$level->id,
                'target'=>$faker->numberBetween(1,5),
                'month'=>$month,
                'year'=>$year,
                'datecreate'=>time(),
                'datemodified'=>time(),
                'status'=>2,
            ];
            DB::table('request')->insert($add);
            echo '<pre>';
            print_r($add);
            echo '</pre>';
        }
    }
}
*/
// generate data by calling methods
echo $faker->lastname().' '.$faker->firstname();
echo '<br/>';
// 'Vince Sporer'
echo $faker->email();
echo '<br/>';
// 'walter.sophia@hotmail.com'
echo $faker->text();
echo '<br/>';
die();