<?php
use Illuminate\Database\Capsule\Manager as DB;
$one =  DB::table('cv')->where('id', $id)->first();
if($one) {
    $tech =  DB::table('interview_tech')->where('cv_id', $id)->first();
    if($tech)
    {
        $one->last_level_id  = $tech->level_id;
    }
}