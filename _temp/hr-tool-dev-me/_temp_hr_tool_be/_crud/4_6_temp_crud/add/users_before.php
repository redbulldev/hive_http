<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container,$request,  [
    'username' => v::alnum()->length(3, 150)->notEmpty()->noWhitespace(),
    'email' => v::email()->length(6, 200)->notEmpty(),
    'fullname' => v::length(3, 200)->notEmpty(),
    'role_id' => v::digit()->notEmpty(),
]);

if(DB::table('users')->where('username',trim($data->username))->where('isdelete',0)->count())
{
    throw new Exception('Username already exists');
}

if(DB::table('users')->where('email',trim($data->email))->where('isdelete',0)->count())
{
    throw new Exception('Email already exists');
}

if(!DB::table('role')->where('id',$data->role_id)->where('isdelete',0)->count())
{
    throw new Exception('Role not exist');
}