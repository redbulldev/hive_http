<?php

use Illuminate\Database\Capsule\Manager as DB;

if (!empty($olddata) && !empty($olddata) && !empty($data->title)) {
    $oldtitle = $olddata->title;
    DB::update('UPDATE request set languages = REPLACE(languages,"' . $oldtitle . '","' . $data->title . '") WHERE languages LIKE "%' . $oldtitle . '%"');
}
