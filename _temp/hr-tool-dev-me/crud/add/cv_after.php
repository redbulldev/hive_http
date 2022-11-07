<?php
use Illuminate\Database\Capsule\Manager as DB;


if(!empty($data->request_id))
{
    updateReport($data->request_id);
}

if(!empty($id) && !empty($idlog))
DB::table('cv_history')->insertGetId([
    'cv_id'=>trim($id),
    'author_id'=>$user->username,
    'description'=>'Cv được tạo mới bởi '. $user->username,
    'datecreate'=>time(),
    'idlog'=>$idlog
]);