<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container,$request, [
    'title' => v::length(3, 200)->notEmpty()
]);
if (!empty($data->title)) {
    $where = ['title' => trim($data->title)];
    if (DB::table('menus')->where($where)->where('isdelete', 0)->count()) {
        throw new Exception('Món ăn đã tồn tại');
    }
}