<?php
use Illuminate\Database\Capsule\Manager as DB;


if(isset($olddata->request_id))
{
    updateReport($olddata->request_id);
}


if(isset($id) && isset($idlog))
DB::table('cv_history')->insertGetId([
    'cv_id'=>trim($id),
    'author_id'=>$user->username,
    'description'=>'Cv đã bị xóa ',
    'datecreate'=>time(),
    'idlog'=>$idlog
]);