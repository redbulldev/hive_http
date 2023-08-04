<?php

use Illuminate\Database\Capsule\Manager as DB;
if (!empty($params['user']))
{
    $user=DB::table('users')->where(['username'=>$params['user']])->first();
    if($user)
    {   
        if($user->isdelete==0)
        {
            if ($user->status == 1) {
                $datauser=['fullname'=> $user->fullname, 'email'=>$user->email, 'mobile' => $user->mobile,'islunch'=>$user->default_lunch?true:false];
                $menu_id = $user->menu_id;
                if (empty($menu_id)) {
                    $office = DB::table('offices')->where('id', $user->office_id)->first();
                    if ($office) {
                        $menu_id = $office->menu_id;
                    }
                }
                if (!empty($menu_id)) {
                    $datauser['default'] = DB::table('menus')->where('id', $menu_id)->select(['id', 'title', 'description', 'status'])->first();
                }
                if($user->default_lunch)
                {
                    $datauser['today'] = DB::table('history')->where(['username' => $user->username,'date'=>date('Y-m-d')])
                        ->select(['id', 'menu', 'price', 'store_id', 'menu_id', 'office_id', 'booked', 'ate'])->first();
                }
                $moreresults['user'] = $datauser;
            }else throw new Exception('User is blocked in Booking lunch');
        }else  throw new Exception('User is deleted from Booking lunch');
    }else throw new Exception('User does not exist in Booking lunch');


}

$moreresults['X-Userinfo'] = !empty($headers['X-Userinfo'])? $headers['X-Userinfo']:'';
$moreresults['X-Access-Token'] = !empty($headers['X-X-Access-Token']) ? $headers['X-Access-Token'] : '';
$moreresults['X-Id-Token'] = !empty($headers['X-Id-Token']) ? $headers['X-Id-Token'] : '';