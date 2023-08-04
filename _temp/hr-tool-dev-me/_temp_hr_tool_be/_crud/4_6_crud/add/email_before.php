<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container, $request,  [
    'cv_step' => v::digit(),
    'title' =>  v::length(2, 200)->notEmpty()
]);
if(!isset($data->cv_status))
{
    throw new Exception('Bạn cần gửi lên trạng thái');
}
if (isset($data->cv_step) && isset($data->cv_status)) {
    if (DB::table('email')->where(['cv_step'=>trim($data->cv_step), 'cv_status' => trim($data->cv_status), 'isdelete'=>0])->count()>0) {
        throw new Exception('Mẫu email cho Step và trạng thái CV đã tồn tại');
    }
}
