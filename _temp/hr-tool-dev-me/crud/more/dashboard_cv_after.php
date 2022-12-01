<?php

use Illuminate\Database\Capsule\Manager as DB;

error_reporting (E_ALL ^ E_NOTICE);

$obj = DB::table($name);

require './crud/all_where.php';

//////////////////// *Bảng xếp hạng số lượng đã tuyển ////////////////////////////////////////////////////////
// data - department //
$department = ['labels' => [], 'values' => []];

$temp_department = [];

$get_position_ids = $obj->get();

$get_positions = DB::table('positions')->where(['isdelete' => 0, 'status' => 1])->get();

$position_ids = [];

foreach ($get_position_ids as $key => $value) {
    foreach ($get_positions as $index => $l) {
        if ($l->id == $value->position_id) {
            // $department['labels'][$l->id] = $l->title;
            $temp_department[$index] = $l->title;

            $position_ids[$index] = $l->id;
        }
    }
}
foreach ($temp_department as $index => $value) {
    $department['labels'][] = $value;
}

// die($response->withJson($department));

foreach ($position_ids as $key => $k) {
    $position_request = clone $obj; //request

    $res = $position_request->where('cv.position_id', $k)->selectRaw('count(cv.step) AS total_cv')->where('cv.step', '>', 8)->where('cv.status', 2)->first(); 

    $department['values'][] = $res->total_cv; //yêu cầu (số lượng)
}

////////////////// Tổng điểm/////////////////////////
$all_level_positions = DB::table('level_positions')->where(['isdelete' => 0])->where('position_id', '!=', 0)->get();

$count_point = 0;

foreach ($all_level_positions as $key => $value) {
    foreach ($position_ids as $index => $k) {
        if ($value->position_id == $k) {
            $count_point += $value->point;
        }
    }
}

/////////////////// data - summary ///////////////////////////////////////////////////////
function getPosition($value)
{
    $position = DB::table('positions')->where('id', $value)->where(['status' => 1, 'point_status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($position)) {
        return $position->title;
    }

    return false;
}

$list_cv_pass = [];

$obj_step = clone $obj->get();

foreach ($obj_step as $key => $value) {
    if ($value->step > 5) {
        if (!empty(getPosition($value->position_id)) && $value->status == 2) {
            $index = getPosition($value->position_id);
            
            $list_cv_pass[$index]++;
        }
    }
}
// die($response->withJson($list_cv_pass));

$obj->selectRaw(' 
    GROUP_CONCAT(cv.position_id) AS list_cv_new, 
    GROUP_CONCAT(cv.step,\'\') AS list_cv_pass, 
    SUM(target) AS target, 
    SUM(total_cv) AS total_cv, 
    SUM(interview_cv) AS interview_cv, 
    SUM(pass_cv) AS pass_cv, 
    SUM(offer_cv) AS offer_cv, 
    SUM(offer_success) AS offer_success, 
    SUM(onboard_cv) AS onboard_cv, 
    SUM(fail_job) AS fail_job,
    GROUP_CONCAT(target) as list_target, 
    GROUP_CONCAT(total_cv) as list_total, 
    GROUP_CONCAT(onboard_cv) as list_onboard,
    GROUP_CONCAT(positions.title,\'\') as labels
');


$summary = $obj->first();
// die($response->withJson($summary));

$list_target = explode(',', $summary->list_target);

// $list_cv_new = explode(',',$summary->cv_new);

$list_total = explode(',', $summary->list_total);

$list_onboard = explode(',', $summary->list_onboard);

$labels = explode(',', $summary->labels);

$newlabel = [];

$newlist_target = [];

$newlist_cv_pass = [];

$newlist_cv_new = [];

$newlist_total = [];

$newlist_onboard = [];

$newlist_cv_pass = [];

foreach ($labels as $key => $label) {
    if ($label) {
        $newlabel[$label] = $label;

        $newlist_target[$label]= (!empty($newlist_target[$label])?$newlist_target[$label]:0) + $list_target[$key];

        $newlist_cv_pass[$label] = (!empty($list_cv_pass[$label]) ? $newlist_cv_pass[$label] + 1 : 0);

        $newlist_cv_new[$label] = (!empty($newlist_cv_new[$label]) ? $newlist_cv_new[$label] : 0) + 1;

        $newlist_total[$label] = (!empty($newlist_total[$label]) ? $newlist_total[$label] : 0) + $list_total[$key];

        $newlist_onboard[$label] = (!empty($newlist_onboard[$label]) ? $newlist_onboard[$label] : 0) + $list_onboard[$key];
    }
}

foreach ($newlist_cv_pass as $key => $item) {
    foreach ($list_cv_pass as $index => $value) {
        if ($key == $index) {
            $newlist_cv_pass[$key] = $newlist_cv_pass[$key] - $list_cv_pass[$index];
        }
    }
}
// die($response->withJson($newlist_cv_pass));

$summary->labels = implode(',', array_keys($newlabel));

$summary->list_target = implode(',',array_values($newlist_target));

$summary->list_cv_pass = implode(',', array_values($newlist_cv_pass));

$summary->list_cv_new = implode(',', array_values($newlist_cv_new));

$summary->list_total = implode(',', array_values($newlist_total));

$summary->list_onboard = implode(',', array_values($newlist_onboard));


$results = [
    'status' => 'success',
    'point' => $count_point,
    'summary' => $summary,
    'department' => $department,
    'data' => $ketqua ? $ketqua->all() : null,
    'total' => $ketqua ? $ketqua->count() : null,
    'time' => time(),
];
