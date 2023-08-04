<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;
throwError($container,$request, [
    'title' => v::length(3, 200)->notEmpty()
]);

if(isset($data->title))
{
    if(DB::table($name)->where('title',trim($data->title))->where('id','!=',trim($id))->where('isdelete',0)->count())
    {
        throw new Exception('Title already exists');
    }
}

if(isset($data->description))
{
    $data->description = substr($data->description,0,5000);
}