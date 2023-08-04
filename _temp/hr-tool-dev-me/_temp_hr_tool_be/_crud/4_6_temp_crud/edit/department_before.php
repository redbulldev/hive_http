<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;
$name='positions';
throwError($container,$request, [
    'title' => v::length(2, 200)->notEmpty()
]);
$data->parent_id =0;
if(isset($data->description))
{
    $data->description = substr($data->description,0,5000);
}

if(isset($data->title))
{
    if(DB::table($name)->where(['title'=>trim($data->title),'parent_id'=>0])->where('id','!=',trim($id))->where('isdelete',0)->count())
    {
        throw new Exception('Title already exists');
    }
}


