<?php

use Illuminate\Database\Capsule\Manager as DB;

$one->position =  DB::table('positions')->where('id',$one->position_id)->first();

$one->request =  DB::table('request')->where('id', $one->request_id)->first();

$one->level =  DB::table('level')->where('id', $one->level_id)->first();

if(!empty($one->last_level_id)){
    $one->last_level = DB::table('level')->where('id', $one->last_level_id)->first();
}




// echo $one->last_level_id;
// print_r($one->last_level_id);

// die($one->last_level_id);

//     $one->last_level = DB::table('level')->where('id', $one->last_level_id)->first();

// $one->last_level = DB::table('level')->where('id', $one->last_level_id)->first();

// $one->last_level 
// $test = DB::table('level')->where('id', $one->last_level_id)->first();
// die($response->withJson($test));
// die($test);
// if(!empty($test)){
//     $one->last_level = DB::table('level')->where('id', $one->last_level_id)->first();
// }