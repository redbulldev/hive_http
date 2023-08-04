<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

try {
    throwError($container, $request, [
        'user' => v::length(3, 200)->notEmpty(),
        'select' => v::length(2, 3)->notEmpty()
    ]);
    $body = json_decode($request->getBody());
    if (!empty($body->menu_id)) {
        $menu = DB::table('menus')->where('id', $body->menu_id)->where(['isdelete' => 0, 'status' => 1])->select(['id', 'title', 'description'])->first();
        if (!$menu) {
            throw new Exception('Menu not exist');
        }
    }
    if(strtolower($body->select)=='on' || strtolower($body->select) == 'off')
    {
        $user = DB::table('users')->where(['username' => $body->user])->first();
        if ($user) {
            if ($user->isdelete == 0) {
                if ($user->status == 1) {
                    $dataup= ['default_lunch' => $body->select == 'on' ? 1 : 0];
                    if (!empty($body->menu_id)) {
                        $dataup['menu_id'] = $body->menu_id;
                    }
                    DB::table('users')->where(['username' => $user->username])->update($dataup);
                    $results = ['status' =>'success'];
                    if (!empty($body->menu_id)) {
                        $results['data'] = $menu;
                    }
                } else throw new Exception('User is blocked in Booking lunch');
            } else  throw new Exception('User is deleted from Booking lunch');
        } else throw new Exception('User does not exist in Booking lunch');
    }else throw new Exception('Select value must be "ON" or "OFF"');
} catch (Exception $e) {
    $obj = @json_decode($e->getMessage());
    if (is_object($obj)) {
        $httpStatus = $obj->status;
        $results = ['status' => 'error', 'message' => $obj->message, 'code' => $obj->code];
    } else {
        $httpStatus = 201;
        $results = ['status' => 'error', 'message' => $e->getMessage()];
    }
}

