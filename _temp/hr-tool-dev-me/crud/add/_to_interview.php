<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;
throwError($container,$request,  [
    'cv_id' => v::digit()->notEmpty(),
    'appoint_type' => v::length(1, 1),
    'appoint_date' => v::digit()->length(10, 10)->notEmpty(),
    'appoint_place' => v::length(3, 200)->notEmpty(),
]);
if(!isset($data->appoint_type))
{
    throw new Exception('Type Interview is 0 or 1');
}

$cv = DB::table('cv')->where('id',trim($data->cv_id))->where('isdelete',0)->first();
if(!$cv)
{
    throw new Exception('CV not exist');
}
 //Kiểm tra bước CV có phù hợp với lần Review không
 if($cv->step < 3)
 {
     throw new Exception('Can\'t create To Interview. Please create CV Review before');
 }
 if($cv->step === 3 && $cv->status !==2)
{
    throw new Exception('Can\'t create To Interview. Status for  CV Review not pass');
}
$newdata= [
    'appoint_type'=>$data->appoint_type,
    'appoint_date'=>$data->appoint_date,
    'appoint_place'=>$data->appoint_place,
    'appoint_link'=> !empty($data->appoint_link)?$data->appoint_link:''
];
if(!empty($data->interviewer_id))
{
    $newdata['interviewer_id'] = $data->interviewer_id;
}
$datacv = cvStep($cv);
$newdata['step']= $datacv['step']>4?$datacv['step']:4;
$newdata['status']= $datacv['status'];

DB::table('cv')->where('id',trim($data->cv_id))->update($newdata);
$idlog = historySave($user->username, 'update', 'cv', $cv->id, $cv);
$description=$user->username.' cập nhật thông tin To Interview';
DB::table('cv_history')->insertGetId([
    'cv_id'=>trim($cv->id),
    'author_id'=>$user->username,
    'description'=>$description,
    'datecreate'=>time(),
    'idlog'=>$idlog
]);