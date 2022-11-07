<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;
if(isset($data->point_tech))
{
    throwError($container,$request, [
        'point_tech' => v::digit()->between(1, 10)->notEmpty(),
    ]);
}
if(isset($data->point_handler))
{
    throwError($container,$request, [
        'point_handler' => v::digit()->between(1, 10)->notEmpty(),
    ]);
}
if(isset($data->point_thinking))
{
    throwError($container,$request, [
        'point_thinking' => v::digit()->between(1, 10)->notEmpty(),
    ]);
}


if(isset($data->level_id))
{
    throwError($container,$request, [
        'level_id' => v::digit()->notEmpty()
    ]);
    if(!DB::table('level')->where('id',$data->level_id)->where('isdelete',0)->count())
    {
        throw new Exception('Level not exist');
    }
}

require('cv_review_interview.php');