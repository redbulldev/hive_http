<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

try {
    function createUserHis($username)
    {
        $allhis = DB::table('history')->where(['username' => $username])->limit(5)->orderBy('date', 'DESC')->get()->toArray();
        $historyData = ['history' => json_encode($allhis)];
        DB::table('users')->where(['username' => $username])->update($historyData);
    }
    throwError($container, $request, [
        'user' => v::length(3, 200)->notEmpty(),
    ]);
    $body = json_decode($request->getBody());
    if (isset($body->default) && $body->default !== true) {
        throw new Exception('Default value must be "true" or remove this key');
    }
    if (!empty($body->menu_id)) {
        $menu = DB::table('menus')->where('id', $body->menu_id)->where(['isdelete'=> 0,'status'=>1])->select(['id', 'title', 'description'])->first();
        if (!$menu) {
            throw new Exception('Menu not exist');
        }
    }
    $user = DB::table('users')->where(['username' => $body->user])->first();
    if ($user) {
        if ($user->isdelete == 0) {
            if ($user->status == 1) {
                //if ($user->default_lunch == 1) {
                    $office = DB::table('offices')->where('id', $user->office_id)->first();
                    if ($office) {
                        $current = DateTime::createFromFormat('H:i:s', date('H:i:s'));
                        $starttime = DateTime::createFromFormat('H:i:s', $office->starttime);
                        $endtime = DateTime::createFromFormat('H:i:s', $office->endtime);
                        if ($current >= $starttime && $current <= $endtime) {
                            if (!empty($body->menu_id)) {
                                //Cập nhật món ăn
                                $storemenu = DB::table('offices_menus')->where(['menu_id' => $body->menu_id, 'office_id' => $user->office_id])->first();
                                if ($storemenu) {
                                    if (isset($body->default)) {
                                        DB::table('users')->where(['username' => $user->username])->update(['menu_id' => $body->menu_id]);
                                    }
                                    $his = DB::table('history')->where(['username' => $user->username, 'date' => date('Y-m-d ')])->first();
                                    if ($his) {
                                        $edit = [
                                            'menu' => $menu->title,
                                            'price' => $storemenu->price,
                                            'store_id' => $storemenu->store_id,
                                            'menu_id' => $menu->id,
                                            'booked' => 1,
                                            'ate' => 1,
                                            'datemodified' => date('Y-m-d H:i:s')
                                        ];
                                        DB::table('history')->where(['id' => $his->id])->update($edit);
                                    } else {
                                        $add = [
                                            'username' => $user->username,
                                            'date' => date('Y-m-d'),
                                            'menu' => $menu->title,
                                            'price' => $storemenu->price,
                                            'store_id' => $storemenu->store_id,
                                            'menu_id' => $menu->id,
                                            'office_id' => $user->office_id,
                                            'booked' => 1,
                                            'ate' => 1,
                                            'datemodified' => date('Y-m-d H:i:s')
                                        ];
                                        DB::table('history')->insert($add);
                                    }
                                    $results = ['status' => 'success','action'=>'choice','data'=> $menu];
                                } else throw new Exception('This menu is not available in stores');
                            } else {
                                if (isset($body->default)) {
                                    DB::table('users')->where(['username' => $user->username])->update(['menu_id' => null]);
                                }
                                $his = DB::table('history')->where(['username' => $user->username, 'date' => date('Y-m-d ')])->first();
                                if ($his) {
                                    //Hủy ăn trưa
                                    $edit = [
                                        'menu' => null,
                                        'price' => null,
                                        'store_id' => null,
                                        'menu_id' => null,
                                        'booked' => 0,
                                        'ate' => 0,
                                        'datemodified' => date('Y-m-d H:i:s')
                                    ];
                                    DB::table('history')->where(['id' => $his->id])->update($edit);
                                }
                                $results = ['status' =>'success', 'action' => 'destroy'];
                            }
                            createUserHis($user->username);
                        } else throw new Exception('Expired time for order and edit lunch');
                    } else throw new Exception('Office not found');
                //} else throw new Exception('User don`t regitry lunch');
            } else throw new Exception('User is blocked in Booking lunch');
        } else  throw new Exception('User is deleted from Booking lunch');
    } else throw new Exception('User does not exist in Booking lunch');
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
