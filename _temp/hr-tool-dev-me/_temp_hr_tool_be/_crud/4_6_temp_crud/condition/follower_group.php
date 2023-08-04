<?php

use Respect\Validation\Validator as v;
use Illuminate\Database\Capsule\Manager as DB;

$follower_group = DB::table('follower_group')->where('cv_id', $data->cv_id)->first();

$data_users = json_decode($follower_group->users);

$arrMember = [];

foreach ($data_users as $key => $value) {
    // if($user->username !==  $value->username){
        $arrMember[$key] = $value->username;

        // echo  $value->username;
    // }
}

// echo 'count:'.count($arrMember);