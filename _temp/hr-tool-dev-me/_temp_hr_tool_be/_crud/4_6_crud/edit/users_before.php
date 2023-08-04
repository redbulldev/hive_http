<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;
//$olddata= DB::table($name)->where($columnpri, $id)->first();
$validation = $container['validationService']->validate($request, [
    'username' => v::alnum()->length(3, 150)->notEmpty()->noWhitespace(),
    'email' => v::email()->length(6, 200)->notEmpty(),
    'fullname' => v::length(3, 200)->notEmpty(),
    'role_id' => v::digit()->notEmpty(),
]);
unset($data->username);

if (!$validation->hasPassed()) {
    foreach ($validation->getErrors() as $input => $errors) {
        foreach ($errors as $error) {
            throw new Exception( $error);
        }
    }
}

// if(DB::table('users')->where('username',trim($data->username))->where('id','!=',$id)->count())
// {
//     throw new Exception('Username already exists');
// }
if(!empty($data->email))
{
    if(DB::table('users')->where('email',trim($data->email))->where('username','!=',$id)->where('isdelete',0)->count())
    {
        throw new Exception('Email already exists');
    }
}
if(!empty($data->role_id))
{
    if(!DB::table('role')->where('id',$data->role_id)->where('isdelete',0)->count())
    {
        throw new Exception('Role not exist');
    }
}