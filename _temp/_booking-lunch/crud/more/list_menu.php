<?php

use Illuminate\Database\Capsule\Manager as DB;
$obj->join('vmenus', 'vmenus.menu_id', '=', 'menus.id');
if (!empty($params['user']))
{
    $user = DB::table('users')->where(['username' => $params['user']])->first();
    if ($user) 
    {
        $obj->join('offices_menus', function($join) use ($user)
            {
                $join->on('menus.id', '=', 'offices_menus.menu_id');
                $join->on('offices_menus.office_id','=',DB::raw($user->office_id));
            });
    }
}
