<?php

use Illuminate\Database\Capsule\Manager as DB;

if(!empty($data->isdefault) && !empty($data->office_id))
{
    $dataupdate=[];
    if(!empty($data->store_id)) $dataupdate['store_id'] = $data->store_id;
    if(!empty($data->menu_id)) $dataupdate['menu_id'] = $data->menu_id;
    if(count($dataupdate))
    {
        DB::table('offices')->where('id', $data->office_id)->update($dataupdate);
    }
}