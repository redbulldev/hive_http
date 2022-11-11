<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container,$request, [
    'title' => v::length(3, 200)->notEmpty()
]);
if(!empty($data->parent_id))
{

    if(!DB::table($name)->where(['id'=>trim($data->parent_id),'parent_id'=>0])->where('isdelete',0)->count())
    {
        throw new Exception('Department not exist');
    }
    if(empty($data->manager_id))
    {
        throw new Exception('Manager not found');
    }else{
        if(!DB::table('users')->where('username',$data->manager_id)->where('isdelete',0)->count())
        {
            throw new Exception('Manager not exist');
        }
    }

    if(empty($data->requestor) ||!is_array($data->requestor) || count($data->requestor)==0)
    {
        throw new Exception('Requestor not found');
    }


    if(isset($data->requestor) && is_array($data->requestor) && count($data->requestor)>0)
    {
        if(count($data->requestor) != DB::table('users')->whereIn('username',$data->requestor)->where('isdelete',0)->count())
        {
            throw new Exception('One of the requesters not found');
        }
    }
}else{
    $data->parent_id =0;
}

if(isset($data->description))
{
    $data->description = substr($data->description,0,5000);
}

if(isset($data->title))
{
    if(!empty($data->parent_id))
    {
        if(DB::table($name)->where(['title'=>trim($data->title),'parent_id'=>$data->parent_id])->where('isdelete',0)->count())
        {
            throw new Exception('Title already exists');
        }
    }else{
        if(DB::table($name)->where(['title'=>trim($data->title),'parent_id'=>0])->where('isdelete',0)->count())
        {
            throw new Exception('Title already exists');
        }
    }
}


ID vị trí/Phòng ban;21
parent_id
ID phòng ban;4
title
Tên vị trí/Phòng ban;Sales
author_id
ID người tạo;namng
manager_id
ID người quản lý;dungptp
requestor
Danh sách ID requestor;["chaupt","trangpm"]
description
Mô tả;Lorem ipsum dolor sit amet...
datecreate
Ngày tạo;1622504033
datemodified
Ngày sửa cuối;1622505033
status
Trạng thái:1 Hoạt động,0 khoá;1
point_status
Tính điểm quy đổi;0;1
isdelete