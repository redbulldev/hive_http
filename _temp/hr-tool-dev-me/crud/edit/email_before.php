<?php

use Illuminate\Database\Capsule\Manager as DB;


if (isset($data->cv_step) && isset($data->cv_status)) {
    if (DB::table('email')->where(['cv_step'=>trim($data->cv_step), 'cv_status' => trim($data->cv_status), 'isdelete'=>0])->where('id','!=',$id)->count()>0) {
        throw new Exception('Mẫu email cho Step và trạng thái CV đã tồn tại');
    }
}
