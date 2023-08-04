<?php
use Illuminate\Database\Capsule\Manager as DB;
$cv =  DB::table('cv')->where(['id'=> $id])->first();
if($cv)
{
    $one=[
        "cv_id"=>$id,
        "interviewer_id"=>$cv->interviewer_id,
        "appoint_type"=> $cv->appoint_type,
        "appoint_date"=> $cv->appoint_date,
        "appoint_place"=> $cv->appoint_place,
        "appoint_link"=> $cv->appoint_link
    ];
}else $one=null;