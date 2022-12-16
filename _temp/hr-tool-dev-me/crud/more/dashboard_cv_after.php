<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj = DB::table($name);

require './crud/all_where.php';

function getPosition($value)
{
    $position = DB::table('positions')->where('id', $value)->where(['status' => 1, 'point_status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($position)) {
        return $position->title;
    }

    return false;
}

// summary //
$temp_list_cv_pass = [];

$interview_cv = 0;

foreach ($obj->get()as $key => $value) {
    if ($value->step > 5 && !empty(getPosition($value->position_id))) {
        $index = getPosition($value->position_id);

        $temp_list_cv_pass[$index] = 0;
    }

    if ($value->step > 4) {
        $interview_cv++;
    }
}

$list_cv_pass = [];

foreach ($temp_list_cv_pass as $key => $value) {
    $list_cv_pass[$key] = $value;
}

foreach ($obj->get()as $key => $value) {
    if ($value->step > 5 && !empty(getPosition($value->position_id))) {
        $index = getPosition($value->position_id);

        array_key_exists($index, $list_cv_pass) ?  $list_cv_pass[$index]++ : 0;
    }
}

$obj_cv = clone $obj;

$obj_cv->selectRaw('
    GROUP_CONCAT(cv.position_id) AS list_cv_new, 
    GROUP_CONCAT(cv.step,\'\') AS list_cv_pass, 
    GROUP_CONCAT(positions.title,\'\') as labels
');

$summary = $obj_cv->first();

// "list_cv_new": "6,6,4,2,2,2,1,1",
// "list_cv_pass": "0,0,0,1,1,0,0,1",
// "labels": "test 7,test 1,Tester,Front-end,test 3,Fullstack,test 6,test 2",
// "interview_cv": 5,
// "total_cv": 24,
// "pass_cv": 3,
// "onboard_cv": 2,
// "target": 229

// die($response->withJson($ketqua->count()));
// die($response->withJson($ketqua->all()));
// die($response->withJson($obj->count()));
// die($response->withJson($obj->get()));

$newlabel = [];

$newlist_cv_pass = [];

$newlist_cv_new = [];

$labels = explode(',', $summary->labels);

foreach ($labels as $key => $label) {
    if ($label) {
        $newlabel[$label] = $label;

        $newlist_cv_new[$label] = (!empty($newlist_cv_new[$label]) ? $newlist_cv_new[$label] : 0) + 1;

        $newlist_cv_pass[$label] = (!empty($list_cv_pass[$label]) ? $list_cv_pass[$label] : 0);
    }
}

arsort($newlist_cv_new);

$temp_cv_pass = [];

$temp_newlabel = [];

foreach ($newlist_cv_new as $key => $new) { 
        $temp_cv_pass[$key] = $new;

        $temp_newlabel[$key] = 1;
}

foreach ($newlist_cv_pass as $key => $value) {
    foreach ($temp_cv_pass as $index => $pass) {
        if ($key == $index) {
            $temp_cv_pass[$index] = $value;
        }
    }
}

$summary->labels = implode(',', array_keys($temp_newlabel));

$summary->list_cv_new = implode(',', array_values($newlist_cv_new));

$summary->interview_cv =  !empty($interview_cv) ? $interview_cv : 0;

$summary->list_cv_pass = implode(',', array_values($temp_cv_pass));

$summary->total_cv = !empty($newlist_cv_new) ? (int)array_sum($newlist_cv_new) : 0;

$summary->pass_cv = !empty($newlist_cv_pass) ? (int)array_sum($newlist_cv_pass) : 0;

// department //
$department = ['labels' => [], 'values' => []];

$temp_department_lables = [];

foreach ($obj->get()as $key => $value) {
    if (!empty(getPosition($value->position_id))) {
        $index = getPosition($value->position_id);

        $temp_department_lables[$value->position_id] = $index;
    }
}

$temp_department_values = [];

foreach ($obj->get()as $key => $value) {
    if ($value->step > 8 && $value->isdelete == 0) {
        $temp_department_values[$value->position_id] = 0;
    }
}

$position_point_ids = [];

foreach ($obj->get()as $key => $value) {
    if ($value->step > 8 && $value->isdelete == 0) {
        $temp_department_values[$value->position_id]++;
    }

    if (!array_key_exists($value->position_id, $temp_department_values)) {
        $temp_department_values[$value->position_id] = 0;
    }

    $position_point_ids[$value->position_id] = $value->position_id;
}

arsort($temp_department_values);

foreach ($temp_department_values as $index => $value) {
    $department['values'][] = $value;
}

foreach ($temp_department_values as $key => $value) {
    foreach ($temp_department_lables as $index => $lable) {
        if ($index == $key) {
            $department['labels'][] = $lable;
        }
    }
}

$summary->onboard_cv = (int)array_sum($department['values']);

// point //
$all_level_positions = DB::table('level_positions')->where(['isdelete' => 0])->where('position_id', '!=', 0)->whereIn('position_id', $position_point_ids)->get();

$count_point = 0;

foreach ($all_level_positions as $key => $value) {
    $count_point += $value->point;
}

// target //
$obj_plan = DB::table('plan');

if (!empty($params['from']) && !empty($params['to'])) {
    $obj_plan->groupBy('year')->groupBy('month')->whereIn('status', [2, 4])
        ->selectRaw("SUM(target) AS target");

    if (!empty($params['from']) && !empty($params['to'])) {
        $from = substr($params['from'], 0, 7) . '-01';

        $to = substr($params['to'], 0, 7) . '-31';

        $obj_plan->where('date', '>=', $from)->where('date', '<=', $to);
    }
}

$target_cv = 0;

foreach ($obj_plan->get() as $key => $value) {
    $target_cv += $value->target;
}

$summary->target = !empty($target_cv) ? $target_cv : 0;


$results = [
    'status' => 'success',
    'point' => $count_point,
    'summary' => $summary,
    'department' => $department,
    'data' => $ketqua ? $ketqua->all(): null,
    'total' => $ketqua ? $ketqua->count() : null,
    'time' => time(),
];




// Phòng ban : positions_title  
// Vị trí : department_title
// Số lượng CV: total_cv
// Số CV tham dự buổi phỏng vấn :  interview_cv  
// Số CV pass phỏng vấn:   pass_cv
// Số UV được offer:  offer_cv 
// Offer thành công :  offer_success  
// Số lượng UV đã đi làm :  onboard_cv
// Tỉ lệ offer/ yêu cầu:  (offer_success/target)  * 100 
// Tỉ lệ onboard/ yêu cầu:  (onboard_cv, target) * 100
// Tỉ lệ onboard/ tỉ lệ offer :  (onboard_cv, offer_success) * 100
// Ngày hoàn thành : item.month/item.year
// Số người fail thử việc: fail_job
// Danh sách UV đi làm: employees
// Trình độ: levels


//////////
// report //
// $obj_request = DB::table('request');

// $position_request_ids = [];

// foreach ($obj->get()as $key => $value) {
//     $position_request_ids[$value->position_id] = $value->position_id;
// }

// $obj_request->whereIn('position_id', $position_request_ids)->where(['request.isdelete' => 0]);

// $obj_request->join('positions', function ($join) {
//     $join->on('positions.id', '=', 'request.position_id');

//     $join->where(['positions.status' => 1, 'positions.isdelete' => 0]);
// });

// $obj_request->join('positions as parent', function ($join) {
//     $join->on('parent.id', '=', 'positions.parent_id');

//     $join->where(['parent.status' => 1, 'parent.isdelete' => 0]);
// });

// $obj_request->selectRaw('positions.title AS position_title, parent.title AS parent_title, request.*');

// if (!empty($params['from']) && !empty($params['to'])) {
//     $from = $params['from'];

//     $to = $params['to'];

//     $obj_request->where('date', '>=', $from)->where('date', '<=', $to);
// }

// if (!empty($params['assignee_id'])) {
//     $assignee_id = explode('-', $params['assignee_id']);

//     $obj_request->where(function ($query) use ($assignee_id) {
//         foreach ($assignee_id as $key  => $id) {
//             $query->orWhere('request.assignee_id', 'LIKE', "%$id%");
//         }
//     });
// }

// if (!empty($params['department_id'])) {
//     $department_id = explode('-', $params['department_id']);

//     $obj_request->where(function ($query) use ($department_id) {
//         foreach ($department_id as $id) {
//             $query->orWhere('positions.parent_id', 'LIKE', "%$id%");
//         }
//     });
// }

// $ketqua = $obj_request ? $obj_request->get() : null;

// $total = $obj_request ? $obj_request->count() : null;

// $results = [
//     'status' => 'success',
//     'point' => $count_point,
//     'summary' => $summary,
//     'department' => $department,
//     'data' => $ketqua,
//     'total' => $total,
//     'time' => time(),
// ];





///////////////////////////////////////////////////////////////////////////
// use Illuminate\Database\Capsule\Manager as DB;

// error_reporting(E_ALL ^ E_NOTICE);

// $obj = DB::table($name);

// require './crud/all_where.php';

// $department = ['labels' => [], 'values' => []];

// $temp_department = [];

// $get_position_ids = $obj->get();

// $get_positions = DB::table('positions')->where(['isdelete' => 0, 'status' => 1])->get();

// $position_ids = [];

// foreach ($get_position_ids as $key => $value) {
//     foreach ($get_positions as $index => $l) {
//         if ($l->id == $value->position_id) {
//             $temp_department[$index] = $l->title;

//             $position_ids[$index] = $l->id;
//         }
//     }
// }

// foreach ($temp_department as $index => $value) {
//     $department['labels'][] = $value;
// }

// $position_request = clone $obj;

// $check_positions = $position_request->whereIn('cv.position_id', $position_ids)->where(['cv.isdelete' => 0])->get();

// $temp_values = [];

// foreach ($check_positions as $key => $value) {
//     if ($value->step > 8 && $value->status == 2) {
//         $temp_values[$value->position_id] = 0;
//     }
// }

// foreach ($check_positions as $key => $value) {
//     if ($value->step > 8 && $value->status == 2) {
//         $temp_values[$value->position_id]++;
//     }

//     if (!array_key_exists($value->position_id, $temp_values)) {
//         $temp_values[$value->position_id] = 0;
//     }
// }

// foreach ($temp_values as $key => $value) {
//     $department['values'][] = $value;
// }

// $all_level_positions = DB::table('level_positions')->where(['isdelete' => 0])->where('position_id', '!=', 0)->get();

// $count_point = 0;

// foreach ($all_level_positions as $key => $value) {
//     foreach ($position_ids as $index => $k) {
//         if ($value->position_id == $k) {
//             $count_point += $value->point;
//         }
//     }
// }

// function getPosition($value)
// {
//     $position = DB::table('positions')->where('id', $value)->where(['status' => 1, 'point_status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

//     if (!empty($position)) {
//         return $position->title;
//     }

//     return false;
// }

// $obj_step = clone $obj->get();

// $temp_list_cv_pass = [];

// foreach ($obj_step as $key => $value) {
//     if ($value->step > 5 && !empty(getPosition($value->position_id)) && $value->status == 2) {
//         $index = getPosition($value->position_id);

//         $temp_list_cv_pass[$index] = 0;
//     }
// }

// $list_cv_pass = [];

// foreach ($temp_list_cv_pass as $key => $value) {
//     $list_cv_pass[$key] = $value;
// }

// foreach ($obj_step as $key => $value) {
//     if ($value->step > 5 && !empty(getPosition($value->position_id)) && $value->status == 2) {
//         $index = getPosition($value->position_id);

//         array_key_exists($index, $list_cv_pass) ?  $list_cv_pass[$index]++ : 0;
//     }
// }

// $obj->selectRaw(' 
//     GROUP_CONCAT(cv.position_id) AS list_cv_new, 
//     GROUP_CONCAT(cv.step,\'\') AS list_cv_pass, 
//     SUM(target) AS target, 
//     SUM(total_cv) AS total_cv, 
//     SUM(interview_cv) AS interview_cv, 
//     SUM(pass_cv) AS pass_cv, 
//     SUM(offer_cv) AS offer_cv, 
//     SUM(offer_success) AS offer_success, 
//     SUM(onboard_cv) AS onboard_cv, 
//     SUM(fail_job) AS fail_job,
//     GROUP_CONCAT(target) as list_target, 
//     GROUP_CONCAT(total_cv) as list_total, 
//     GROUP_CONCAT(onboard_cv) as list_onboard,
//     GROUP_CONCAT(positions.title,\'\') as labels
// ');

// $summary = $obj->first();

// $list_target = explode(',', $summary->list_target);

// $list_total = explode(',', $summary->list_total);

// $list_onboard = explode(',', $summary->list_onboard);

// $labels = explode(',', $summary->labels);

// $newlabel = [];

// $newlist_target = [];

// $newlist_cv_pass = [];

// $newlist_cv_new = [];

// $newlist_total = [];

// $newlist_onboard = [];

// $newlist_cv_pass = [];

// foreach ($labels as $key => $label) {
//     if ($label) {
//         $newlist_cv_pass[$label] = 0;       
//     }
// }

// foreach ($labels as $key => $label) {
//     if ($label) {
//         $newlabel[$label] = $label;

//         $newlist_target[$label] = (!empty($newlist_target[$label]) ? $newlist_target[$label] : 0) + $list_target[$key];

//         $newlist_cv_pass[$label] = (!empty($list_cv_pass[$label]) ? $newlist_cv_pass[$label] + 1 : 0);

//         $newlist_cv_new[$label] = (!empty($newlist_cv_new[$label]) ? $newlist_cv_new[$label] : 0) + 1;

//         $newlist_total[$label] = (!empty($newlist_total[$label]) ? $newlist_total[$label] : 0) + $list_total[$key];

//         $newlist_onboard[$label] = (!empty($newlist_onboard[$label]) ? $newlist_onboard[$label] : 0) + $list_onboard[$key];
//     }
// }

// foreach ($newlist_cv_pass as $key => $item) {
//     foreach ($list_cv_pass as $index => $value) {
//         if ($key == $index && $value != 1) {
//             $newlist_cv_pass[$key] = $newlist_cv_pass[$key] - $list_cv_pass[$index];
//         }
//     }
// }

// $summary->labels = implode(',', array_keys($newlabel));

// $summary->list_target = implode(',', array_values($newlist_target));

// $summary->list_cv_pass = implode(',', array_values($newlist_cv_pass));

// $summary->list_cv_new = implode(',', array_values($newlist_cv_new));

// $summary->list_total = implode(',', array_values($newlist_total));

// $summary->list_onboard = implode(',', array_values($newlist_onboard));

// $results = [
//     'status' => 'success',
//     'point' => $count_point,
//     'summary' => $summary,
//     'department' => $department,
//     'data' => $ketqua ? $ketqua->all(): null,
//     'total' => $ketqua ? $ketqua->count() : null,
//     'time' => time(),
// ];
